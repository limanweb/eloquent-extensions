<?php

namespace Limanweb\EloquentExt\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasCompositePrimaryKey
{
    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }
    
    /**
     * Check for primary key is composite
     *
     * @return boolean
     */
    public function hasCompositePrimaryKey()
    {
        return is_array($this->primaryKey ?? null);
    }
    
    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        
        if ($this->hasCompositePrimaryKey()) {
            foreach ($this->getKeyName() as $partKeyName) {
                $keyValue = $this->getAttribute($partKeyName);
                if (!isset($keyValue)) {
                    throw new \Exception(__METHOD__ . ' : Missing part of the primary key: ' . $partKeyName);
                }
                $query->where($partKeyName, '=', $keyValue);
            }
        }
        
        return $query;
    }
    
    /**
     * Execute a query for a single record by ID.
     *
     * @param  array  $id Array of keys, like [column => value].
     * @param  array  $columns
     * @return mixed|static
     */
    public static function find($id, $columns = ['*'])
    {
        $instance = new self;
        
        if ($instance->hasCompositePrimaryKey()) {
            
            $query = $instance->newQuery();
            
            if (!is_array($id)) {
                throw new \Exception(__METHOD__ . ' : Argument 0 must be an array for composite primary key');
            }
            
            if (count($id) != count($keyName = $instance->getKeyName())) {
                throw new \Exception(__METHOD__ . ' : Count of items in argument 0 must be equal count of composite primary key parts');
            }
            
            $i = 0;
            foreach ($keyName as $keyPartName) {
                if (!is_scalar($keyVal = $id[$i])) {
                    throw new \Exception(__METHOD__ . ' : Every item of argument 0 must be scalar');
                }
                $query->where($keyPartName, '=', $keyVal);
                $i++;
            }
            
            return $query->first($columns);
        }
        
        return parent::find($id, $columns);
    }
    
    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey()
    {
        if ($this->hasCompositePrimaryKey()) {
            $key = [];
            foreach ($this->getKeyName() as $partKeyName) {
                $key[] = $this->getAttribute($partKeyName);
            }
            
            return $key;
        }
        
        return parent::getKey();
    }
}