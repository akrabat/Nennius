<?php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'album' => 'Gallery\Controller\AlbumController',
            ),
            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'options'  => array(
                        'script_paths' => array(
                            'album' => __DIR__ . '/../views',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
