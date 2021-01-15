<?php namespace LearnKit\H5p\Models;

use Model;

class EventLog extends Model
{
    public $table = 'h5p_event_logs';

    protected $fillable = [
        'user_id',
        'created_at',
        'type',
        'sub_type',
        'content_id',
        'content_title',
        'library_name',
        'library_version',
    ];
}
