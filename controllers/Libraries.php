<?php namespace Kloos\H5p\Controllers;

use App;
use File;
use Flash;
use Backend;
use Exception;
use BackendMenu;
use BackendAuth as Auth;
use Kloos\H5p\Models\Library;
use Backend\Classes\Controller;

/**
 * Libraries Back-end Controller
 */
class Libraries extends Controller
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

    public $formWidget;

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Kloos.H5p', 'h5p', 'libraries');
    }

    public function upload()
    {
        $config = $this->makeConfig('$/kloos/h5p/models/library/upload.yaml');

        $config->model = new Library;

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->alias = 'uploadForm';
        $widget->bindToController();

        $this->formWidget = $widget;

        $this->vars['form'] = $widget;
    }

    public function upload_onSave()
    {
        $h5p = App::make('OctoberH5p');
        $core = $h5p::$core;

        $content = [
            'disable'    => \H5PCore::DISABLE_NONE,
            'user_id'    => Auth::getUser()->id,
            'title'      => 'Library content',
            'embed_type' => 'div',
            'filtered'   => '',
            'slug'       => config('laravel-h5p.slug'),
        ];

        $content['filtered'] = '';

        try {
            $content['uploaded'] = true;

            $this->get_disabled_content_features($core, $content);

            // Handle file upload
            $return_id = $this->handleUpload($content);

            Flash::success('H5P library imported');

            return redirect(Backend::url('kloos/h5p/libraries'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());

            return 0;
        }
    }

    public static function get_disabled_content_features($core, &$content)
    {
        $set = [
            \H5PCore::DISPLAY_OPTION_FRAME     => filter_input(INPUT_POST, 'frame', FILTER_VALIDATE_BOOLEAN),
            \H5PCore::DISPLAY_OPTION_DOWNLOAD  => filter_input(INPUT_POST, 'download', FILTER_VALIDATE_BOOLEAN),
            \H5PCore::DISPLAY_OPTION_EMBED     => filter_input(INPUT_POST, 'embed', FILTER_VALIDATE_BOOLEAN),
            \H5PCore::DISPLAY_OPTION_COPYRIGHT => filter_input(INPUT_POST, 'copyright', FILTER_VALIDATE_BOOLEAN),
        ];
        $content['disable'] = $core->getStorableDisplayOptions($set, $content['disable']);
    }

    public function handleUpload($content = null, $only_upgrade = null, $disable_h5p_security = false) {
        $h5p = App::make('OctoberH5p');
        $core = $h5p::$core;
        $validator = $h5p::$validator;
        $interface = $h5p::$interface;
        $storage = $h5p::$storage;

        // Get the uploaded file
        $model = new Library();
        $key = input('_session_key');
        $file = $model->temp_file()->withDeferred($key)->first();

        if ($disable_h5p_security) {
            // Make it possible to disable file extension check
            $core->disableFileCheck = (filter_input(INPUT_POST, 'h5p_disable_file_check',
                FILTER_VALIDATE_BOOLEAN) ? true : false);
        }

        // Move so core can validate the file extension.
        //rename($_FILES['temp_file']['tmp_name'], $interface->getUploadedH5pPath());

        File::copy($file->getLocalPath(), $interface->getUploadedH5pPath());

        $skipContent = ($content === null);

        if ($validator->isValidPackage($skipContent, $only_upgrade) && ($skipContent || $content['title'] !== null)) {
            if (function_exists('check_upload_size')) {
                // Check file sizes before continuing!
                $tmpDir = $interface->getUploadedH5pFolderPath();
                $error = self::check_upload_sizes($tmpDir);
                if ($error !== null) {
                    // Didn't meet space requirements, cleanup tmp dir.
                    $interface->setErrorMessage($error);
                    \H5PCore::deleteFileTree($tmpDir);

                    return false;
                }
            }
            // No file size check errors
            if (isset($content['id'])) {
                $interface->deleteLibraryUsage($content['id']);
            }

            $storage->savePackage($content, null, true);

            // Clear cached value for dirsize.
            return $storage->contentId;
        }
        // The uploaded file was not a valid H5P package
        @unlink($interface->getUploadedH5pPath());

        return false;
    }
}
