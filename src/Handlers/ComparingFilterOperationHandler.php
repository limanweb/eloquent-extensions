<?php 

namespace Limanweb\EloquentExt\Handlers;

use Limanweb\EloquentExt\Handlers\Contracts\FilterOperationHandlerInterface;
use Limanweb\EloquentExt\Handlers\AbstractFilterOperationHandler;
use Illuminate\Database\Eloquent\Builder;

class ComparingFilterOperationHandler extends AbstractFilterOperationHandler implements FilterOperationHandlerInterface
{
   
    /**
     * eq
     * 
     * @param Builder $query
     * @param unknown $field
     * @param unknown $operation
     * @param unknown $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyEq(Builder $query, string $field, string $operation, $value) {
        return $query->where($field, '=', $value);
    }
    
    /**
     * not-eq
     *
     * @param Builder $query
     * @param unknown $field
     * @param unknown $operation
     * @param unknown $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyNotEq(Builder $query, string $field, string $operation, $value) {
        return $query->where($field, '<>', $value);
    }
    
    /**
     * gt
     *
     * @param Builder $query
     * @param unknown $field
     * @param unknown $operation
     * @param unknown $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyGt(Builder $query, string $field, string $operation, $value) {
        return $query->where($field, '>', $value);
    }
    
    /**
     * ge
     *
     * @param Builder $query
     * @param unknown $field
     * @param unknown $operation
     * @param unknown $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyGe(Builder $query, string $field, string $operation, $value) {
        return $query->where($field, '>=', $value);
    }
    
    /**
     * lt
     *
     * @param Builder $query
     * @param unknown $field
     * @param unknown $operation
     * @param unknown $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyLt(Builder $query, string $field, string $operation, $value) {
        return $query->where($field, '<', $value);
    }
    
    /**
     * le
     *
     * @param Builder $query
     * @param unknown $field
     * @param unknown $operation
     * @param unknown $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyLe(Builder $query, string $field, string $operation, $value) {
        return $query->where($field, '<=', $value);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Limanweb\EloquentExt\Handlers\AbstractFilterOperationHandler::validate()
     */
    public function validate(Builder $query, string $field, string $operation, $value, &$error = null)
    {
        
        
        if (!is_scalar($value)) {
            $error = trans(
                'eloquent-extensions::errors.field_filter_operation_value_must_be_scalar',
                ['field' => $field, 'operation' => $operation]
            );
            return false;
        }
        
        return true;
    }
}