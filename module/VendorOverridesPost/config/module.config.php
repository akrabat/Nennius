<?php
return array(
    'di' => array(
        'instance' => array(
            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'resolver' => 'Zend\View\TemplatePathStack',
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
