<?php namespace Kloos\H5p\Models;

use App;
use Model;

/**
 * Library Model
 */
class Library extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'h5p_libraries';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'title',
        'major_version',
        'minor_version',
        'patch_version',
        'runnable',
        'restricted',
        'fullscreen',
        'embed_types',
        'preloaded_js',
        'preloaded_css',
        'drop_library_css',
        'semantics',
        'tutorial_url',
        'has_icon',
        'created_at',
        'updated_at',
    ];

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
    protected $jsonable = [];

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
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function numContent()
    {
        $h5p = App::make('OctoberH5p');
        $interface = $h5p::$interface;

        return intval($interface->getNumContent($this->id));
    }

    public function getCountContentDependencies()
    {
        $h5p = App::make('OctoberH5p');
        $interface = $h5p::$interface;
        $usage = $interface->getLibraryUsage($this->id, $interface->getNumNotFiltered() ? true : false);

        return intval($usage['libraries']);
    }
}
