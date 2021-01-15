<?php namespace LearnKit\H5p\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Dashboard extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        BackendMenu::setContext('LearnKit.H5p', 'h5p', 'dashboard');
    }
}
