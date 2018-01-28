<?php

namespace CodyMoorhouse\Secretary\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'section_id',
        'section_ref_slug',
        'user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
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
        $model->setTable(config('secretary.notes_table'));
        return $model;
    }

    /**
     * Get the comments for the note.
     */
    public function comments()
    {
        return $this->hasMany('CodyMoorhouse\Secretary\Models\Comment');
    }

    /**
     * Get the comments for the note.
     */
    public function media()
    {
        return $this->hasMany('CodyMoorhouse\Secretary\Models\Media');
    }
}
