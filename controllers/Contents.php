<?php namespace LearnKit\H5p\Controllers;

use Flash;
use Backend;
use BackendMenu;
use Backend\Classes\Controller;
use LearnKit\H5p\Models\Content;

/**
 * Contents Back-end Controller
 */
class Contents extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    /**
     * @var string Configuration file for the `FormController` behavior.
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string Configuration file for the `ListController` behavior.
     */
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('LearnKit.H5p', 'h5p', 'contents');
    }

    public function onReplicate($id)
    {
        $content = Content::find($id);
        try {
            $newContent = $content->duplicate();
            Flash::success('Content duplicated');
            return redirect(Backend::url('learnkit/h5p/contents/update/' . $newContent->id));
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
        }
    }
}
