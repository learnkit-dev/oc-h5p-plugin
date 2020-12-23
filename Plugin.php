<?php namespace Kloos\H5p;

use Config;
use Backend;
use BackendAuth;
use System\Classes\PluginBase;
use Illuminate\Foundation\AliasLoader;
use Djoudi\LaravelH5p\Helpers\H5pHelper;
use Djoudi\LaravelH5p\Commands\ResetCommand;
use Djoudi\LaravelH5p\Commands\MigrationCommand;

/**
 * H5p Plugin Information File
 */
class Plugin extends PluginBase
{
    protected $defer = false;

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Djoudi\LaravelH5p\Events\H5pEvent' => [
            'Djoudi\LaravelH5p\Listeners\H5pNotification',
        ],
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
            'author'      => 'Kloos',
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
        $this->bootPackages();

        $this->app->singleton('LaravelH5p', function ($app) {
            $LaravelH5p = new \Kloos\H5p\Classes\OctoberH5p($app);

            return $LaravelH5p;
        });

        $this->app->singleton('OctoberH5p', function ($app) {
            $LaravelH5p = new \Kloos\H5p\Classes\OctoberH5p($app);

            return $LaravelH5p;
        });

        $this->app->bind('H5pHelper', function () {
            return new H5pHelper();
        });

        $this->app->singleton('command.laravel-h5p.migration', function ($app) {
            return new MigrationCommand();
        });

        $this->app->singleton('command.laravel-h5p.reset', function ($app) {
            return new ResetCommand();
        });

        $this->commands([
            'command.laravel-h5p.migration',
            'command.laravel-h5p.reset',
        ]);

        $this->app->bind('Illuminate\Contracts\Auth\Factory', function () {
            return BackendAuth::instance();
        });
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        /*

        // language
        $this->publishes([
            __DIR__.'/../../lang/en/laravel-h5p.php' => resource_path('lang/en/laravel-h5p.php'),
        ], 'language');
        $this->publishes([
            __DIR__.'/../../lang/fr/laravel-h5p.php' => resource_path('lang/fr/laravel-h5p.php'),
        ], 'language');
        $this->publishes([
            __DIR__.'/../../lang/ar/laravel-h5p.php' => resource_path('lang/ar/laravel-h5p.php'),
        ], 'language');

        */

        // views
        $this->publishes([
            __DIR__.'/../../views/h5p' => resource_path('views/h5p'),
        ], 'resources');

        // migrations
        $this->publishes([
            __DIR__.'/../../migrations/' => database_path('migrations'),
        ], 'migrations');

        // h5p
        $this->publishes([
            app_path('/../vendor/kloos-dev/laravel-h5p/assets') => public_path('vendor/laravel-h5p'),
            app_path('/../vendor/h5p/h5p-core/fonts')           => public_path('vendor/h5p/h5p-core/fonts'),
            app_path('/../vendor/h5p/h5p-core/images')          => public_path('vendor/h5p/h5p-core/images'),
            app_path('/../vendor/h5p/h5p-core/js')              => public_path('vendor/h5p/h5p-core/js'),
            app_path('/../vendor/h5p/h5p-core/styles')          => public_path('vendor/h5p/h5p-core/styles'),
            app_path('/../vendor/h5p/h5p-editor/ckeditor')      => public_path('vendor/h5p/h5p-editor/ckeditor'),
            app_path('/../vendor/h5p/h5p-editor/images')        => public_path('vendor/h5p/h5p-editor/images'),
            app_path('/../vendor/h5p/h5p-editor/language')      => public_path('vendor/h5p/h5p-editor/language'),
            app_path('/../vendor/h5p/h5p-editor/libs')          => public_path('vendor/h5p/h5p-editor/libs'),
            app_path('/../vendor/h5p/h5p-editor/scripts')       => public_path('vendor/h5p/h5p-editor/scripts'),
            app_path('/../vendor/h5p/h5p-editor/styles')        => public_path('vendor/h5p/h5p-editor/styles'),
        ], 'public');
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Kloos\H5p\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'kloos.h5p.some_permission' => [
                'tab' => 'H5p',
                'label' => 'Some permission'
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
                'url'         => Backend::url('kloos/h5p/dashboard'),
                'iconSvg'     => '/plugins/kloos/h5p/h5p.svg',
                'permissions' => ['kloos.h5p.*'],
                'order'       => 500,
                'sideMenu' => [
                    'dashboard' => [
                        'label' => 'Dashboard',
                        'url' => Backend::url('/kloos/h5p/dashboard'),
                        'icon' => 'icon-dashboard',
                    ],
                    'contents' => [
                        'label' => 'Contents',
                        'url' => Backend::url('/kloos/h5p/contents'),
                        'icon' => 'icon-list',
                    ],
                ],
            ],
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Kloos\H5p\FormWidgets\H5pEditor' => 'h5peditor',
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
