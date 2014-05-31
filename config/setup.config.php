<?php
/**
 * Setup configuration file
 *
 * Don't worry about this file. It is irrelevant for the actual system
 * and is only used for the first setup.
 */

/**
 * Development mode
 *
 * Should always be true in this context!
 */
define('EP3_HS_DEV', true);

/**
 * Setup configuration array
 */
return array(
    'modules' => array(
        'Base',
        'Room',
        'Setup',
        'User',
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            'module',
            'vendor',
        ),
        'config_cache_enabled' => ! EP3_HS_DEV,
        'config_cache_key' => 'ep3-hs-setup',
        'module_map_cache_enabled' => ! EP3_HS_DEV,
        'module_map_cache_key' => 'ep3-hs-setup',
        'cache_dir' => getcwd() . '/data/cache/',
    ),
);