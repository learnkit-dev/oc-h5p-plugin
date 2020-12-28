<?php namespace Kloos\H5p\Models;

use Model;

class ContentsUserData extends Model
{
    protected $table = 'h5p_contents_user_data';

    protected $fillable = [
        'content_id',
        'user_id',
        'sub_content_id',
        'data_id',
        'data',
        'preload',
        'invalidate',
        'updated_at',
    ];
}
