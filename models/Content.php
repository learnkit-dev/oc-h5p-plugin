<?php namespace LearnKit\H5p\Models;

use App;
use Model;
use BackendAuth;
use Backend\Models\User;
use LearnKit\H5p\Classes\H5pEvent;
use LearnKit\H5p\Http\H5pController;

/**
 * Content Model
 */
class Content extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'h5p_contents';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [
        'parameters',
        'filtered',
        'metadata',
    ];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $hasOneThrough = [];
    public $hasManyThrough = [];

    public $belongsTo = [
        'library' => Library::class,
        'backend_user' => [
            User::class,
            'key' => 'user_id',
        ],
    ];

    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function getEmbedUrlAttribute()
    {
        return url('h5p/embed/' . $this->id);
    }

    public function beforeSave()
    {
        $h5p = App::make('OctoberH5p');
        $core = $h5p::$core;

        $parameters = json_decode(input('parameters'), true);

        if (post('library')) {
            $library = $core->libraryFromString(post('library'));
            $this->library_id = $core->h5pF->getLibraryId($library['machineName'], $library['majorVersion'], $library['minorVersion']);
        }

        if (input('parameters')) {
            $this->title = $parameters['metadata']['title'];
            $this->parameters = $parameters['params'];
            $this->metadata = $parameters['metadata'];
            $this->slug = str_slug($this->title) . '-' . uniqid();
        }

        $this->user_id = BackendAuth::getUser()->id;
        $this->filtered = '';
        $this->embed_type = 'div';
    }

    public function afterSave()
    {
        $h5p = App::make('OctoberH5p');
        $core = $h5p::$core;
        $editor = $h5p::$h5peditor;

        $event_type = 'update';
        $content = $h5p::get_content($this->id);

        if (!$content['library']) {
            return;
        }

        $content['embed_type'] = 'div';
        $content['user_id'] = BackendAuth::getUser()->id;
        $content['disable'] = input('disable') ? input('disable') : false;
        $content['filtered'] = '';

        $oldLibrary = $content['library'];
        $oldParams = json_decode($content['params']);

        $content['library'] = $core->libraryFromString(input('library'));

        if (!$content['library']) {
            return;
        }

        // Check if library exists.
        $content['library']['libraryId'] = $core->h5pF->getLibraryId($content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']);
        if (!$content['library']['libraryId']) {
            throw new \H5PException('No such library');
        }

        //                $content['parameters'] = $request->get('parameters');
        //old
        //$content['params'] = $request->get('parameters');
        //$params = json_decode($content['params']);

        //new
        $params = json_decode(input('parameters'));
        $content['params'] = json_encode($params->params);
        if ($params === null) {
            throw new \H5PException('Invalid parameters');
        }

        $content['metadata'] = json_encode($params->metadata);

        //$content['keywords'] = $params->metadata->title;

        // Set disabled features
        H5pController::get_disabled_content_features($core, $content);

        // Save new content
        $core->saveContent($content);

        // Move images and find all content dependencies
        $editor->processParameters($content['id'], $content['library'], $params, $oldLibrary, $oldParams);

        event(new H5pEvent('content', $event_type, $content['id'], $content['title'], $content['library']['machineName'], $content['library']['majorVersion'], $content['library']['minorVersion']));
    }
}
