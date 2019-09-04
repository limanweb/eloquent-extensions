<?php

namespace Limanweb\EloquentExt\Models\Concerns;

/**
 * @author i.khondozhko
 *
 * @desc Trait implements filling for 'created_by', 'updated_by' and 'deleted_by'
 * @desc columns by authorized user ID
 */
trait HasUserstamps
{
    /**
     * @desc To change default 'created_by', 'updated_by' and 'deleted_by' column names you can
     * @desc define constants CREATED_BY, UPDATED_BY and DELETED_BY in conrete model
     */
    
    /**
     * Determine if the model uses userstamps.
     *
     * @return bool
     */
    public function usesUserstamps()
    {
        return $this->userstamps ?? false;
    }
    
    /**
     * Overriding boot()
     */
    public static function boot()
    {
        
        static::creating(function (\Illuminate\Database\Eloquent\Model $model)
        {
            if ($model->usesUserstamps() && \Auth::check()) {
                $model->setCreatedBy(\Auth::user()->id);
            }
        });
        
        static::saving(function (\Illuminate\Database\Eloquent\Model $model)
        {
            if ($model->usesUserstamps() && \Auth::check()) {
                $model->setUpdatedBy(\Auth::user()->id);
            }
        });
        
        static::deleting(function (\Illuminate\Database\Eloquent\Model $model) {
            if ($model->usesUserstamps() && \Auth::check()) {
                $model->setDeletedBy(\Auth::user()->id);
            }
        });
            
        if (method_exists(static::class, 'restoring')) {
            static::restoring(function (\Illuminate\Database\Eloquent\Model $model) {
                if ($model->usesUserstamps()) {
                    $model->setDeletedBy(null);
                }
            });
        }
        
        parent::boot();
    }
    
    /**
     * Set 'created by'
     *
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function setCreatedBy($value)
    {
        if ($this->usesUserstamps()) {
            $this->{$this->getCreatedByColumn()} = $value;
        }
        return $this;
    }
    
    /**
     * Set 'updated by'
     *
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function setUpdatedBy($value)
    {
        if ($this->usesUserstamps()) {
            $this->{$this->getUpdatedByColumn()} = $value;
        }
        return $this;
    }
    
    /**
     * Set 'deleted by'
     *
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function setDeletedBy($value)
    {
        $column = $this->getDeletedByColumn();
        
        if ($this->usesUserstamps()) {
            $this->{$column} = $value;
        }
        
        // Direct update 'deleted by' when soft deleting
        if (property_exists($this, 'forceDeleting') && !$this->forceDeleting) {
            $query = $this->newModelQuery()->where($this->getKeyName(), $this->getKey());
            $query->update([$column => $value]);
        }
        
        return $this;
    }
    
    /**
     * Get 'created by' column name
     *
     * @return string
     */
    public function getCreatedByColumn()
    {
        return defined('static::CREATED_BY') ? static::CREATED_BY : 'created_by';
    }
    
    /**
     * Get 'updated by' column name
     *
     * @return string
     */
    public function getUpdatedByColumn()
    {
        return defined('static::UPDATED_BY') ? static::UPDATED_BY : 'updated_by';
    }
    
    /**
     * Get 'deleted by' column name
     *
     * @return string
     */
    public function getDeletedByColumn()
    {
        return defined('static::DELETED_BY') ? static::DELETED_BY : 'deleted_by';
    }
    
}
