<?php namespace Kloos\H5p\Http;

use BackendAuth;
use Illuminate\Routing\Controller;
use Djoudi\LaravelH5p\Events\H5pEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class EmbedController extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $h5p = App::make('OctoberH5p');
        $core = $h5p::$core;
        $settings = $h5p::get_editor();
        $content = $h5p->get_content($id);
        $embed = $h5p->get_embed($content, $settings);
        $embed_code = $embed['embed'];
        $settings = $embed['settings'];

        event(new H5pEvent('content', null, $content['id'], $content['title'], $content['library']['name'], $content['library']['majorVersion'], $content['library']['minorVersion']));

        $user = BackendAuth::getUser();

        return view('kloos.h5p::content.embed', [
            'settings' => $settings,
            'user' => $user,
            'embed_code' => $embed_code
        ]);
    }
}
