<?php namespace Kloos\H5p\FormWidgets;

use App;
use Backend\Classes\FormField;
use Backend\Classes\FormWidgetBase;

class H5pEditor extends FormWidgetBase
{
    protected $defaultAlias = 'h5peditor';

    public function init()
    {
        $this->prepareAssets();
        $this->prepareVars();
    }

    public function render()
    {
        return $this->makePartial('h5p_editor');
    }

    public function getSaveValue($value)
    {
        return FormField::NO_SAVE_DATA;
    }

    public function prepareVars()
    {
        $parameters = [];

        $parameters['params'] = json_decode($this->data->parameters);
        $parameters['metadata'] = json_decode($this->data->metadata);
        $parameters = \json_encode($parameters);

        if ($this->data->library) {
            $library = $this->data->library->toArray();

            $library['majorVersion'] = $library['major_version'];
            $library['minorVersion'] = $library['minor_version'];

            $library = \H5PCore::libraryToString($library);
        } else {
            $library = 0;
        }

        $this->vars['parameters'] = $parameters;
        $this->vars['library'] = $library;
    }

    public function prepareAssets()
    {
        $this->addJs('js/event-helper.js');

        $h5p = App::make('OctoberH5p');
        $settings = $h5p::get_editor();

        foreach ($settings['core']['scripts'] as $script) {
            $this->addJs($script);
        }

        foreach ($settings['core']['styles'] as $style) {
            $this->addCss($style);
        }

        $this->addJs('/h5pintegration-settings.js');
        $this->addJs('js/october-h5p.js');
    }
}
