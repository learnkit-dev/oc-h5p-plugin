<?php namespace Kloos\H5p\Classes;

use BackendAuth as Auth;

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
