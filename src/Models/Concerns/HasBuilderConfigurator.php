<?php 

namespace Limanweb\EloquentExt\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Limanweb\EloquentExt\Services\BuilderConfiguratorService;

trait HasBuilderConfigurator
{
    
    /**
     * 
     * Apply request params to query
     * 
     * @param Builder $query
     * @param Request $request
     * @param array $profile
     * @return Builder
     */
    public function scopeBuildWithRequest(Builder $query, Request $request, array $profile = [])
    {
        return app()->make(BuilderConfiguratorService::class)
            ->buildWithRequest($query, $request, $profile);
    }

    /**
     * Public alias for getCastType() 
     * 
     * Notes: Required public function for BuilderConfiguratorService 
     * 
     * @param string $field
     * @return string|NULL
     */
    public function getFieldCastType(string $field) 
    {
        return $this->getCastType($field);
    }
    
    
}