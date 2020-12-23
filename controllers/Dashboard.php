<?php namespace Kloos\H5p\Controllers;

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
        BackendMenu::setContext('Kloos.H5p', 'h5p', 'dashboard');
    }
}
