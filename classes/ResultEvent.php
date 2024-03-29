<?php namespace LearnKit\H5p\Classes;

use Auth;

class ResultEvent
{
    public $user_id;

    public $result;

    public function __construct($type, $sub_type, $result)
    {
        $this->user_id = Auth::getUser()->id;
        $this->result = $result;
    }
}
