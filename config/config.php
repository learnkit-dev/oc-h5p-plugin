<?php return [
    // This contains the Laravel Packages that you want this plugin to utilize listed under their package identifiers
    'packages' => [
        'kloos-dev/laravel-h5p' => [
            // Service providers to be registered by your plugin
            'providers' => [
            ],

            // Aliases to be registered by your plugin in the form of $alias => $pathToFacade
            'aliases' => [
            ],

            // The namespace to set the configuration under. For this example, this package accesses it's config via config('purifier.' . $key), so the namespace 'purifier' is what we put here
            'config_namespace' => 'laravel-h5p',

            // The configuration file for the package itself. Start this out by copying the default one that comes with the package and then modifying what you need.
            'config' => [
                'H5P_DEV'         => false,
                'language'        => 'en',
                'domain'          => env('APP_URL', 'http://localhost'),
                'h5p_public_path' => '/vendor',
                'slug'            => 'laravel-h5p',
                'views'           => 'h5p', // h5p view path
                'layout'          => 'kloos.h5p::layouts.h5p', // layoute path
                'use_router'      => 'ALL', // ALL,EXPORT,EDITOR

                'H5P_DISABLE_AGGREGATION' => false,

                // Content screen setting
                'h5p_show_display_option'    => true,
                'h5p_frame'                  => true,
                'h5p_export'                 => false,
                'h5p_embed'                  => true,
                'h5p_copyright'              => false,
                'h5p_icon'                   => false,
                'h5p_track_user'             => '1',
                'h5p_ext_communication'      => true,
                'h5p_save_content_state'     => true,
                'h5p_save_content_frequency' => 1,
                'h5p_site_key'               => [
                    'h5p_h5p_site_uuid'      => false,
                ],
                'h5p_content_type_cache_updated_at' => 0,
                'h5p_check_h5p_requirements'        => false,
                'h5p_hub_is_enabled'                => false,
                'h5p_version'                       => '1.23.0',
            ],
        ],
    ],
];
