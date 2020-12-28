<?php namespace Kloos\H5p\Components;

use Kloos\H5p\Models\Content;
use Cms\Classes\ComponentBase;

class H5pEmbed extends ComponentBase
{
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

    public function getContentIdOptions()
    {
        return Content::all()
            ->pluck('title', 'id')
            ->toArray();
    }
}
