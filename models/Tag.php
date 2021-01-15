<?php namespace LearnKit\H5p\Models;

use Model;

class Tag extends Model
{
    protected $fillable = [
        'type',
        'library_name',
        'library_version',
        'num',
    ];

    public $table = 'h5p_tmpfiles';

    protected $guarded = ['*'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = [
        'type',
        'library_name',
        'library_version',
    ];
}
