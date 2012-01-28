<?php
return array(
    'di' => array(
        'instance' => array(
            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'options'  => array(
                        'script_paths' => array(
                            'vendoroverridespost' => __DIR__ . '/../views',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
