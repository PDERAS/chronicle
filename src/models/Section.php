<?php

namespace CodyMoorhouse\Chronicle\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access_type',
        'allow_atachments',
        'allow_tasks',
        'commenting',
        'requires_approval',
        'title',
        'tag',
        'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'updated_at',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'tag';
    }

    /**
     * Create a new instance of the given model.
     *
     * @var array $attributes
     * @var bool $exists
     * @return Illuminate\Database\Eloquent\Model
     */
    public function newInstance($attributes = [], $exists = false)
    {
        $model = parent::newInstance($attributes, $exists);
        $model->setTable(config('chronicle.sections_table'));
        return $model;
    }

    /**
     * Get the notes that section contains.
     */
    public function notes()
    {
        return $this->hasMany('CodyMoorhouse\Chronicle\Models\Note');
    }
}
