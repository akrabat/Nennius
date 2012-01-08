<?php
return array(
    'gallery' => array(
        'photo_model_class' => 'Gallery\Model\Photo',
        'file_path' => 'data/photos',
    ),
    'di' => array(
        'instance' => array(
            'alias' => array(
                // Controllers
                'upload'                => 'Gallery\Controller\UploadController',
                'album'                 => 'Gallery\Controller\AlbumController',

                // Other aliases
                'gallery_upload_form'   => 'Gallery\Form\Upload',
                'gallery_photo_service' => 'Gallery\Service\Photo',
                'gallery_photo_mapper'  => 'Gallery\Model\PhotoMapper',
                'gallery_write_db'      => 'Zend\Db\Adapter\DiPdoMysql',
                'gallery_read_db'       => 'gallery_write_db',
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
            'gallery_photo_service' => array(
                'parameters' => array(
                    'photoMapper'     => 'gallery_photo_mapper',
                ),
            ),
            'Gallery\Model\PhotoMapper' => array(
                'parameters' => array(
                    'readAdapter'  => 'edpuser_read_db',
                    'writeAdapter' => 'edpuser_write_db',
                ),
            ),
        ),
    ),
    'routes' => array(
        'photos' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'priority' => 100,
            'options' => array(
                'route' => '/photos/[:username]',
                'defaults' => array(
                    'controller' => 'album',
                    'username' => '',
                ),
            ),
            'may_terminate' => true,
            'child_routes' => array(
                'upload' => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route' => '/upload',
                        'defaults' => array(
                            'controller' => 'upload',
                            'action'     => 'index',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
