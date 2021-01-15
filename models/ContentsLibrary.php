<?php namespace LearnKit\H5p\Models;

use Model;

class ContentsLibrary extends Model
{
    protected $table = 'h5p_contents_libraries';

    protected $primaryKey = ['content_id', 'library_id', 'dependency_type'];
    protected $fillable = [
        'content_id',
        'library_id',
        'dependency_type',
        'weight',
        'drop_css',
    ];
}
