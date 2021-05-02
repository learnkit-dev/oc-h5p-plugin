<?php namespace LearnKit\H5p\Classes;

class H5pRender
{
    public static function render($id, $page)
    {
        /*$h5p = App::make('OctoberH5p');
        $settings = $h5p::get_editor();
        $content = $h5p->get_content($id);
        $embed = $h5p->get_embed($content, $settings);
        $embed_code = $embed['embed'];
        $settings = $embed['settings'];

        $styles = array_merge($settings['core']['styles'], $settings['loadedCss']);
        $scripts = array_merge($settings['core']['scripts'], $settings['loadedJs']);

        event(new H5pEvent('content', null, $content['id'], $content['title'], $content['library']['name'], $content['library']['majorVersion'], $content['library']['minorVersion']));

        $user = Auth::getUser();

        return '<div>
                    {{ embed_code|raw }}
                </div>
                
                <script type="text/javascript">
                    H5PIntegration = {{ embed_settings|json_encode()|raw }};
                </script>';*/
    }
}