<?php namespace LearnKit\H5p;

use Config;
use Backend;
use BackendAuth;
use System\Classes\PluginBase;
use Illuminate\Foundation\AliasLoader;
use LearnKit\H5p\Classes\H5pHelper;

/**
 * H5p Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = [
        'RainLab.User',
    ];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'H5p',
            'description' => 'No description provided yet...',
            'author'      => 'LearnKit',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        $this->bootPackages();

        $this->app->singleton('LaravelH5p', function ($app) {
            $LaravelH5p = new \LearnKit\H5p\Classes\OctoberH5p($app);

            return $LaravelH5p;
        });

        $this->app->singleton('OctoberH5p', function ($app) {
            $LaravelH5p = new \LearnKit\H5p\Classes\OctoberH5p($app);

            return $LaravelH5p;
        });

        $this->app->bind('H5pHelper', function () {
            return new H5pHelper();
        });

        $this->app->bind('Illuminate\Contracts\Auth\Factory', function () {
            return BackendAuth::instance();
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'LearnKit\H5p\Components\H5pEmbed' => 'h5p',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'learnkit.h5p.manage_content' => [
                'tab' => 'H5P',
                'label' => 'Manage H5P content'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'h5p' => [
                'label'       => 'H5P',
                'url'         => Backend::url('learnkit/h5p/dashboard'),
                'iconSvg'     => '/plugins/learnkit/h5p/assets/h5p.svg',
                'permissions' => ['learnkit.h5p.*'],
                'order'       => 500,
                'sideMenu' => [
                    'dashboard' => [
                        'label' => 'Dashboard',
                        'url' => Backend::url('/learnkit/h5p/dashboard'),
                        'icon' => 'icon-dashboard',
                    ],
                    'contents' => [
                        'label' => 'Contents',
                        'url' => Backend::url('/learnkit/h5p/contents'),
                        'icon' => 'icon-list',
                    ],
                    'libraries' => [
                        'label' => 'Libraries',
                        'url' => Backend::url('/learnkit/h5p/libraries'),
                        'icon' => 'icon-list',
                    ],
                ],
            ],
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'LearnKit\H5p\FormWidgets\H5pEditor' => 'h5peditor',
        ];
    }

    /**
     * Boots (configures and registers) any packages found within this plugin's packages.load configuration value
     *
     * @see https://luketowers.ca/blog/how-to-use-laravel-packages-in-october-plugins
     * @author Luke Towers <octobercms@luketowers.ca>
     */
    public function bootPackages()
    {
        // Get the namespace of the current plugin to use in accessing the Config of the plugin
        $pluginNamespace = str_replace('\\', '.', strtolower(__NAMESPACE__));

        // Instantiate the AliasLoader for any aliases that will be loaded
        $aliasLoader = AliasLoader::getInstance();

        // Get the packages to boot
        $packages = Config::get($pluginNamespace . '::packages');

        // Boot each package
        foreach ($packages as $name => $options) {
            // Setup the configuration for the package, pulling from this plugin's config
            if (!empty($options['config']) && !empty($options['config_namespace'])) {
                Config::set($options['config_namespace'], $options['config']);
            }

            // Register any Service Providers for the package
            if (!empty($options['providers'])) {
                foreach ($options['providers'] as $provider) {
                    App::register($provider);
                }
            }

            // Register any Aliases for the package
            if (!empty($options['aliases'])) {
                foreach ($options['aliases'] as $alias => $path) {
                    $aliasLoader->alias($alias, $path);
                }
            }
        }
    }
}
