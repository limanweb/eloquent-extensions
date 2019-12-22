<?php 

namespace Limanweb\EloquentExt\Handlers;

use Limanweb\EloquentExt\Handlers\Contracts\FilterOperationHandlerInterface;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilterOperationHandler implements FilterOperationHandlerInterface
{
    /**
     * 
     * {@inheritDoc}
     * @see \Limanweb\EloquentExt\Handlers\Contracts\FilterOperationHandlerInterface::apply()
     */
    public function apply(Builder $query, string $field, string $operation, $value, &$error = null) 
    {
        $applyMethod = self::getApplyMethodName($operation);
        
        if (!method_exists($this, $applyMethod)) {
            return null;
        }
         
        $castedValue = self::castValue($query, $field, $operation, $value);
        
        if (is_null($castedValue)) {
            $error = trans(
                'eloquent-extensions::errors.field_filter_operation_value_is_incorrect',
                ['field' => $field, 'operation' => $operation]
            );
            
            return $query;
        }
        
        return $this->$applyMethod($query, $field, $operation, $castedValue);
    }

    /**
     * Prepare apply-method name
     * 
     * @param string $operation
     * @return string
     */
    private function getApplyMethodName(string $operation) {
        return 'apply'.Str::ucfirst(Str::camel(str_replace('-', '_', $operation)));
    }
    
    public function castValue(Builder $query, string $field, string $operation, $value, $castType = null)
    {
        
        static $castsConfig;
        
        if (is_null($castsConfig)) {
            $castsConfig = config("eloquent_ext.filters.casts");
        }
        
        $castType = $castType ?? $query->getModel()->getFieldCastType($field);

        $valueType = null;
        if (is_array($value)) {
            foreach ($value as &$itemValue) {
                $itemValue = self::castValue($query, $field, $operation, $itemValue, $castType);
                if (is_null($itemValue)) {
                    return null;
                }
            }
            return $value;
        } elseif (is_string($value)) {
            $valueType = 'string';
        }
        
        $configCast = config("eloquent_ext.filters.casts.{$valueType}.{$castType}");
        
        $castMethod = $castsConfig[$valueType][$castType] ?? null;
        if ($castMethod && method_exists(self::class, $castMethod)) {
            $value = self::$castMethod($value);
        }
        
        return $value;
    }
 
    protected function castIntVal(string $value) {
        if (preg_match('/^[-]?\d+/', $value)) {
            return intval($value);
        }
        return null;
    }
    
    protected function castFloatVal(string $value) {
        if (preg_match('/^\d*\.?\d{0,2}/', $value)) {
            return floatval($value);
        }
        return null;
    }
    
    protected function castBoolVal(string $value) {
        if ($value == '1' || strtolower($value) == 'true') {
            return true;
        } elseif ($value == '0' || strtolower($value) == 'false') {
            return false;
        }
        return null;
    }
    
}