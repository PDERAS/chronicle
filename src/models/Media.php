<?php

namespace CodyMoorhouse\Chronicle\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
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
        'filename',
        'filename_original',
        'file_mime',
        'note_id',
        'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'filename_original',
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
        $model->setTable(config('chronicle.media_table'));
        return $model;
    }

    /**
     * Accessor for the filename attribute
     *
     * @return string
     */
    public function getFilenameAttribute()
    {
        return $this->attributes['filename_original'];
    }
}
