<?php
            // 'upload' => array(
            //     'parameters' => array(
            //         'broker'       => 'Zend\Mvc\Controller\PluginBroker',
            //     ),
            // ),
            //'album' => array(
            //     'parameters' => array(
            //         'broker'       => 'Zend\Mvc\Controller\PluginBroker',
            //     ),
            // ),
return array(
    'gallery' => array(
        'photo_model_class' => 'Gallery\Model\Photo',
        'file_path' => 'data/photos',
        'public_file_path' => 'public/photos',
        'public_file_url' => 'photos',
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

            'gallery_photo_service' => array(
                'parameters' => array(
                    'photoMapper'     => 'gallery_photo_mapper',
                ),
            ),
            'Gallery\Model\PhotoMapper' => array(
                'parameters' => array(
                    'readAdapter'  => 'zfcuser_read_db',
                    'writeAdapter' => 'zfcuser_write_db',
                ),
            ),

            // Set up the view layer.
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'options'  => array(
                        'script_paths' => array(
                            'gallery' => __DIR__ . '/../view',
                        ),
                    ),
                ),
            ),

            // View Helper
            'Zend\View\HelperLoader' => array(
                'parameters' => array(
                    'map' => array(
                        'galleryThumbnail' => 'Gallery\View\Helper\GalleryThumbnail',
                    ),
                ),
            ),
            'Zend\View\HelperBroker' => array(
                'parameters' => array(
                    'loader' => 'Zend\View\HelperLoader',
                ),
            ),
            'Gallery\View\Helper\GalleryThumbnail' => array(
                'parameters' => array(
                    'photoService' => 'gallery_photo_service',
                ),
            ),


            // Setup the router and routes
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
                    'routes' => array(
                        'photos' => array(
                            'type' => 'Literal',
                            'options' => array(
                                'route' => '/photos',
                                'defaults' => array(
                                    'controller' => 'album',
                                    'action'     => 'index',
                                ),
                            ),
                            'may_terminate' => true,
                            'child_routes' => array(
                                'username' => array(
                                    'type' => 'Zend\Mvc\Router\Http\Segment',
                                    'options' => array(
                                        'route' => '/[:displayname]',
                                        'defaults' => array(
                                            'controller' => 'album',
                                            'action'     => 'index',
                                        ),
                                    ),
                                ),
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
                ),
            ),            
        ),
    ),
);
