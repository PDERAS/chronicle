<?php

namespace Pderas\Chronicle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

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
        'section_ref_slug',
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
        $model->setTable(config('chronicle.notes_table'));
        return $model;
    }

    /**
     * Get the created at attribute
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->toIso8601ZuluString() : null;
    }

    /**
     * Get the comments for the note.
     */
    public function comments()
    {
        return $this->hasMany('Pderas\Chronicle\Models\Comment');
    }

    /**
     * Get the comments for the note.
     */
    public function media()
    {
        return $this->hasMany('Pderas\Chronicle\Models\Media');
    }

    /**
     * Gets the user that the note belongs to
     */
    public function user()
    {
        return $this->belongsTo(config('chronicle.users_model'))
                    ->select(
                        config('chronicle.users_table_name') . ' as name',
                        config('chronicle.users_table_id') . ' as id'
                    );
    }
}
