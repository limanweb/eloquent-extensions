<?php 

namespace Limanweb\EloquentExt\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Limanweb\EloquentExt\Handlers\Contracts\FilterOperationHandlerInterface;

class BuilderConfiguratorService
{
    
    const OPT_SORTING       = 'sorting';
    const OPT_FILTER        = 'filter';
    const OPT_FILTER_SCOPE  = 'filterScope';
    const OPT_PAGINATION     = 'pagination';
    
    protected $defaultProfile = [
        'options' => [
            self::OPT_FILTER        => 'f',
            self::OPT_SORTING       => 's',
            self::OPT_FILTER_SCOPE  => 'fs',
            self::OPT_PAGINATION    => 'p',
        ]
    ];

    protected $errors = [];

    /**
     * 
     * @param Builder $query
     * @param Request $request
     * @param array $profile
     * @return Builder
     */
    public function buildWithRequest(Builder $query, Request $request, array $profile = [])
    {
        $params = $request->all();
        $profile = array_replace_recursive($this->defaultProfile, $profile);
        
        // Filters
        $optProfile  = $this->getOptProfile(self::OPT_FILTER, $profile);
        $optParams   = $this->getOptParams(self::OPT_FILTER, $params, $profile);
        if (!empty($optParams)) {
            $query = $this->applyFilter($query, $optParams, $optProfile);
        }
        
        // Sorting
        $optProfile  = $this->getOptProfile(self::OPT_SORTING, $profile);
        $optParams   = $this->getOptParams(self::OPT_SORTING, $params, $profile);
        if (!empty($optParams)) {
            $query = $this->applySorting($query, $optParams, $optProfile);
        }
        
        if ($this->errors) {
            throw new \Limanweb\EloquentExt\Exceptions\BuilderConfiguratorException(
                'eloquent-extensions::errors.builder_configurator_validation_error',
                $this->errors
            );
        }
        
        return $query;
        
    }

    /**
     * 
     * @param string $option
     * @param array $params
     * @param array $profile
     * @return array|NULL
     */
    protected function getOptParams(string $option, array $params, array $profile = null)
    {
        return optional($params)[$profile['options'][$option]] 
            ?? optional($profile)['default'][$profile['options'][$option]] 
            ?? [];
    }
    
    /**
     * 
     * @param string $option
     * @param array $profile
     * @return array|NULL
     */
    protected function getOptProfile(string $option, array $profile = null) 
    {
        return optional($profile)[$profile['options'][$option]] ?? [];
    }
    
    /**
     * Apply sorting options
     *
     * @param Builder $query
     * @param array $params
     * @param array $profile
     * @return Builder
     */
    protected function applySorting(Builder $query, array $params, array $profile)
    {

        foreach ($params as $field => $value) {

            if ($this->validateFieldSorting($field, $value, $profile)) {
                $query = $query->orderBy($field, config('eloquent_ext.sorting.values')[strtoupper($value)]);
            }
        }
        
        return $query;
    }

    protected function validateFieldSorting($field, $value, array $profile) {
        
        $result = true;
        
        if (!in_array($field, $profile)) {
            $this->addError($field, trans('eloquent-extensions::errors.field_sorting_is_not_enabled', ['field' => $field]));
            $result = false;
        }
        
        if (!is_string($value)) {
            $this->addError($field, trans('eloquent-extensions::errors.field_sorting_type_value_is_incorrect', ['field' => $field]));
            $result = false;
        }
        
        if (!isset(config('eloquent_ext.sorting.values')[strtoupper($value)])) {
            $this->addError($field, trans('eloquent-extensions::errors.field_sorting_value_is_incorrect', ['field' => $field, 'value' => $value]));
            $result = false;
        }
        
        return $result;
    }
    
    protected function addError($field, $message) 
    {
        $this->errors[$field][] = $message;
    }
    
    /**
     * 
     * @param Builder $query
     * @param array $params
     * @param array $profile
     * @return Builder
     */
    protected function applyFilter(Builder $query, array $params, array $profile)
    {

        foreach ($params as $field => $filters) {
            
            if (!in_array($field, array_keys($profile))) {
                $this->addError($field, trans(
                    'eloquent-extensions::errors.field_filter_is_not_enabled', 
                    ['field' => $field]
                ));
            }
            
            foreach ($filters as $filterOperation => $filterValue) {
                
                if (!in_array($filterOperation, $profile[$field] ?? [])) {
                    $this->addError($field, trans(
                        'eloquent-extensions::errors.field_filter_operation_is_not_enabled', 
                        ['field' => $field, 'operation' => $filterOperation]
                    ));
                    continue;
                }
                
                $operationHandler = $this->getFilterOperationHandler($field, $filterOperation);
                
                if (!$operationHandler) {
                    $this->addError($field, trans(
                        'eloquent-extensions::errors.field_filter_operation_is_not_exists',
                        ['field' => $field, 'operation' => $filterOperation]
                    ));
                    continue;
                }

                $error = null;
                if (!$operationHandler->validate($query, $field, $filterOperation, $filterValue, $error)) {
                    $this->addError($field, $error);
                    continue;
                }
                
                $error = null;
                $query = $operationHandler->apply($query, $field, $filterOperation, $filterValue, $error);
                if ($error) {
                    $this->addError($field, $error);
                    continue;
                }
                
            }
            
        }
        
        return $query;
    }
    
    /**
     * 
     * @param string $field
     * @param string $filterOperation
     * @return NULL|FilterOperationHandlerInterface
     */
    protected function getFilterOperationHandler(string $field, string $filterOperation)
    {
        $config = config("eloquent_ext.filters.operations.{$filterOperation}");

        if (!class_exists($config['handler'])) {
            return null;
        }
        
        return app()->make($config['handler']);
    }
}