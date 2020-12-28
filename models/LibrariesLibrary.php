<?php namespace Kloos\H5p\Models;

use Model;

class LibrariesLibrary extends Model
{
    protected $table = 'h5p_libraries_libraries';

    protected $primaryKey = ['library_id', 'required_library_id'];

    protected $fillable = [
        'library_id',
        'required_library_id',
        'dependency_type',
    ];
}
