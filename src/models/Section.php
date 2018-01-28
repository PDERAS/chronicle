<?php

namespace CodyMoorhouse\Secretary\Models;

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
        'title',
        'tag',
        'commenting',
        'access_type',
        'type',
        'requires_approval',
        'allow_atachments',
        'allow_tasks',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
    ];

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
        $model->setTable(config('secretary.sections_table'));
        return $model;
    }

    /**
     * Get the notes that section contains.
     */
    public function notes()
    {
        return $this->hasMany('CodyMoorhouse\Secretary\Models\Note');
    }
}
