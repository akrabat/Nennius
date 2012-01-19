<?php
return array(
    'modules' => array(
        'Application',
        'ZfcBase',
        'ZfcUser',
        'VendorOverridesPost',
        'Gallery',
    ),
    'module_listener_options' => array(
        'config_cache_enabled' => false,
        'cache_dir'            => 'data/cache',
        'module_paths' => array(
            './moduledev',
            './module',
            './vendor',
        ),
    ),
);
