<?php namespace LearnKit\H5p\Models;

use Model;

class Result extends Model
{
    protected $table = 'h5p_results';

    protected $fillable = [
        'content_id',
        'subcontent_id',
        'user_id',
        'score',
        'max_score',
        'opened',
        'finished',
        'time',
        'description',
        'correct_responses_pattern',
        'response',
        'additionals',
    ];
}
