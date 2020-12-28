<?php namespace Kloos\H5p\Models;

use App;
use Model;
use BackendAuth;
use Backend\Models\User;

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

        $library = $core->libraryFromString(post('library'));
        $this->library_id = $core->h5pF->getLibraryId($library['machineName'], $library['majorVersion'], $library['minorVersion']);
        $this->title = $parameters['metadata']['title'];
        $this->parameters = $parameters['params'];
        $this->user_id = BackendAuth::getUser()->id;
        $this->filtered = '';
        $this->slug = str_slug($this->title) . '-' . uniqid();
        $this->embed_type = 'div';

        $this->metadata = $parameters['metadata'];
    }
}
