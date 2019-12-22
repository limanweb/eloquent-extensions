<?php 

namespace Limanweb\EloquentExt\Handlers\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterOperationHandlerInterface
{
    
    public function apply(Builder $query, string $field, string $operation, $value, &$error = null);
    
    public function validate(Builder $query, string $field, string $operation, $value, &$error = null);
    
    public function castValue(Builder $query, string $field, string $operation, $value);
    
}