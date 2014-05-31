<?php
/**
 * Application configuration file
 */

/**
 * Development mode
 *
 * If true, errors are displayed.
 * If false, errors are silently logged to an error file.
 *
 * If true, certain caches will be enabled.
 */
define('EP3_HS_DEV', true);

/**
 * Application configuration array
 */
return array(
    'modules' => array(

        /**
         * Application core modules
         *
         * Usually, you don't have to change these
         * (but you can, of course ;)
         */
        'Backend',
        'Base',
        'Bill',
        'Booking',
        'Bundle',
        'Calendar',
        'Frontend',
        'Product',
        'Service',
        'Room',
        'User',

        /**
         * Custom modules
         *
         * Add your own or third party modules here
         */
        // 'MyModule',
    ),

    /**
     * Some further internal settings,
     * don't worry about these.
     */
    'module_listener_options' => array(
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            'module',
            'vendor',
        ),
        'config_cache_enabled' => ! EP3_HS_DEV,
        'config_cache_key' => 'ep3-hs',
        'module_map_cache_enabled' => ! EP3_HS_DEV,
        'module_map_cache_key' => 'ep3-hs',
        'cache_dir' => getcwd() . '/data/cache/',
    ),
);