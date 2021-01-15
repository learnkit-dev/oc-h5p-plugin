<?php namespace LearnKit\H5p\Http;

use BackendAuth;
use Illuminate\Http\Request;
use LearnKit\H5p\Classes\H5pEvent;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

class EmbedController extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $h5p = App::make('OctoberH5p');
        $settings = $h5p::get_editor();
        $content = $h5p->get_content($id);
        $embed = $h5p->get_embed($content, $settings);
        $embed_code = $embed['embed'];
        $settings = $embed['settings'];

        event(new H5pEvent('content', null, $content['id'], $content['title'], $content['library']['name'], $content['library']['majorVersion'], $content['library']['minorVersion']));

        $user = BackendAuth::getUser();

        return view('learnkit.h5p::content.embed', [
            'settings' => $settings,
            'user' => $user,
            'embed_code' => $embed_code
        ]);
    }
}
