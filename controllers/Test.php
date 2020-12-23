<?php namespace Kloos\H5p\Controllers;

use App;
use BackendMenu;
use Backend\Classes\Controller;
use Kloos\H5p\Http\H5pController;
use Djoudi\LaravelH5p\Events\H5pEvent;
use BackendAuth as Auth;
use Djoudi\LaravelH5p\Exceptions\H5PException;

/**
 * Test Back-end Controller
 */
class Test extends Controller
{
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Kloos.H5p', 'h5p', 'test');
    }

    public function index()
    {
        $h5p = App::make('LaravelH5p');

        $settings = $h5p::get_editor();

        $this->addJs(url('/h5pintegration-settings.js'));

        foreach ($settings['core']['scripts'] as $script) {
            $this->addJs($script);
        }

        foreach ($settings['core']['styles'] as $style) {
            $this->addCss($style);
        }

        //event(new H5pEvent('content', 'new'));
    }

    public function onHandleFileUpload()
    {
        $h5p = App::make('OctoberH5p');
        $core = $h5p::$core;
        $editor = $h5p::$h5peditor;

        $oldLibrary = null;
        $oldParams = null;
        $event_type = 'create';

        $content = $h5p::get_content(null);

        $content = [
            'disable'    => \H5PCore::DISABLE_NONE,
            'user_id'    => Auth::getUser()->id,
            'title'      => 'Test 1234',
            'embed_type' => 'div',
            'filtered'   => '',
            'slug'       => config('laravel-h5p.slug'),
        ];

        $content['filtered'] = '';

        if (post('action') == 'create') {
            $content['library'] = $core->libraryFromString(post('library'));

            if (!$content['library']) {
                throw new H5PException('Invalid library.');
            }

            // Check if library exists
            $content['library']['libraryId'] = $core->h5pF->getLibraryId($content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']);
            if (!$content['library']['libraryId']) {
                throw new H5PException('No such library');
            }

            // New
            $params = json_decode(post('parameters'));
            $content['params'] = json_encode($params->params);
            if ($params === null) {
                throw new H5PException('Invalid parameters');
            }

            $content['metadata'] = json_encode($params->metadata);

            static::get_disabled_content_features($core, $content);

            // Save new content
            $content['id'] = $core->saveContent($content);

            // Move images and find all content dependencies
            $editor->processParameters($content['id'], $content['library'], $params, $oldLibrary, $oldParams);

            $return_id = $content['id'];

            return $return_id;
        }

        if (post('action') == 'upload') {
            try {
                $content['uploaded'] = true;

                $this->get_disabled_content_features($core, $content);

                // Handle file upload
                $return_id = $this->handleUpload($content);

                return $return_id;
            } catch (\H5PException $ex) {
                return 0;
            }
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

        if ($disable_h5p_security) {
            // Make it possible to disable file extension check
            $core->disableFileCheck = (filter_input(INPUT_POST, 'h5p_disable_file_check', FILTER_VALIDATE_BOOLEAN) ? true : false);
        }

        // Move so core can validate the file extension.
        rename($_FILES['h5p_file']['tmp_name'], $interface->getUploadedH5pPath());

        $skipContent = ($content === null);

        if ($validator->isValidPackage($skipContent, $only_upgrade) && ($skipContent || $content['title'] !== null)) {
            if (function_exists('check_upload_size')) {
                // Check file sizes before continuing!
                $tmpDir = $interface->getUploadedH5pFolderPath();
                $error = self::check_upload_sizes($tmpDir);
                if ($error !== null) {
                    // Didn't meet space requirements, cleanup tmp dir.
                    $interface->setErrorMessage($error);
                    H5PCore::deleteFileTree($tmpDir);

                    return false;
                }
            }
            // No file size check errors
            if (isset($content['id'])) {
                $interface->deleteLibraryUsage($content['id']);
            }

            $storage->savePackage($content, null, $skipContent);

            // Clear cached value for dirsize.
            return $storage->contentId;
        }
        // The uploaded file was not a valid H5P package
        @unlink($interface->getUploadedH5pPath());

        return false;
    }
}
