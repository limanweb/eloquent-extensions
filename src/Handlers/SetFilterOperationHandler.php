<?php 

namespace Limanweb\EloquentExt\Handlers;

use Limanweb\EloquentExt\Handlers\Contracts\FilterOperationHandlerInterface;
use Limanweb\EloquentExt\Handlers\AbstractFilterOperationHandler;
use Illuminate\Database\Eloquent\Builder;
use Hamcrest\Type\IsScalar;

class SetFilterOperationHandler extends AbstractFilterOperationHandler implements FilterOperationHandlerInterface
{
   
    /**
     * in
     * 
     * @param Builder $query
     * @param unknown $field
     * @param unknown $operation
     * @param unknown $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyIn(Builder $query, string $field, string $operation, array $value) {
        return $query->whereIn($field, $value);
    }
    
    /**
     * not-in
     *
     * @param Builder $query
     * @param unknown $field
     * @param unknown $operation
     * @param unknown $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyNotIn(Builder $query, string $field, string $operation, array $value) {
        return $query->whereNotIn($field, $value);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Limanweb\EloquentExt\Handlers\AbstractFilterOperationHandler::validate()
     */
    public function validate(Builder $query, string $field, string $operation, $value, &$error = null)
    {
        if (!is_array($value)) {
            $error = trans(
                'eloquent-extensions::errors.field_filter_operation_value_must_be_array',
                ['field' => $field, 'operation' => $operation]
            );
            return false;
        }
        
        return true;
    }
}