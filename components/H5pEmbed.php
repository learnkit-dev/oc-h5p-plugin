<?php namespace LearnKit\H5p\Components;

use App;
use Auth;
use LearnKit\H5p\Models\Content;
use Cms\Classes\ComponentBase;
use LearnKit\H5p\Classes\H5pEvent;

class H5pEmbed extends ComponentBase
{
    public $styles;

    public $scripts;

    public $settings;

    public function componentDetails()
    {
        return [
            'name'        => 'H5pEmbed Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'contentId' => [
                'property' => 'contentId',
                'title' => 'Content',
                'type' => 'dropdown',
            ],
        ];
    }

    public function forContentId($id)
    {
        $this->setProperty('contentId', $id);

        $this->onRun();

        return $this->renderPartial('@default');
    }

    public function onRun()
    {
        if (!$this->property('contentId', null)) {
            return;
        }

        //
        $h5p = App::make('OctoberH5p');

        //
        $settings = $this->settings ? $this->settings : $h5p::get_editor();


        $content = $h5p->get_content($this->property('contentId'));
        $embed = $h5p->get_embed($content, $settings);
        $embed_code = $embed['embed'];
        $settings = $embed['settings'];

        $this->styles = array_merge($settings['core']['styles'], $settings['loadedCss']);
        $this->scripts = array_merge($settings['core']['scripts'], $settings['loadedJs']);

        event(new H5pEvent('content', null, $content['id'], $content['title'], $content['library']['name'], $content['library']['majorVersion'], $content['library']['minorVersion']));

        $user = Auth::getUser();

        $this->prepareAssets();

        $this->page['embed_settings'] = $settings;
        $this->page['embed_code'] = $embed_code;

        if ($this->settings) {
            $this->settings['contents'] = array_merge($this->settings['contents'], $settings['contents']);
        } else {
            $this->settings = $settings;
        }

        /*return view('learnkit.h5p::content.embed', [
            'settings' => $settings,
            'user' => $user,
            'embed_code' => $embed_code
        ]);*/

        // Add the integration configuration

    }

    public function prepareAssets()
    {
        foreach ($this->styles as $style) {
            $this->addCss($style);
        }

        foreach ($this->scripts as $script) {
            $this->addJs($script);
        }
    }

    public function getContentIdOptions()
    {
        return Content::all()
            ->pluck('slug', 'id')
            ->toArray();
    }
}
