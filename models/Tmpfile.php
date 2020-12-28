<?php namespace Kloos\H5p\Models;

use Model;

class Tmpfile extends Model
{
    protected $fillable = [
        'path',
        'created_at',
    ];

    public $table = 'h5p_tmpfiles';

    protected $guarded = ['*'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
