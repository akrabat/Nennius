<?php
return array(
    'display_exceptions'    => true,
    
    'view' => array(
        'layout' => 'layouts/layout.phtml',
    ),
    
    'di' => array(
        'definition' => array('class' => array(
            'Zend\View\PhpRenderer' => array(
                'setResolver' => array(
                    'resolver' => array(
                        'required' => true,
                        'type'     => 'Zend\View\Resolver',
                    ),
                ),
            ),
            'Zend\View\Resolver\TemplateMapResolver' => array(
                '__construct' => array(
                    'map' => array(
                        'required' => false,
                        'type'     => false,
                    ),
                ),
            ),
            'Zend\Mvc\View\DefaultRenderingStrategy' => array(
                'setDefaultLayout' => array(
                    'defaultLayout' => array(
                        'required'  => false,
                        'type'      => false,
                    ),
                ),
                'setDisplayExceptions' => array(
                    'displayExceptions' => array(
                        'required' => false,
                        'type'     => false,
                    ),
                ),
                'setEnableLayoutForErrors' => array(
                    'enableLayoutForErrors'=> array(
                        'required' => false,
                        'type'     => false,
                    ),
                ),
                'setLayoutIncapableModels' => array(
                    'layoutIncapableModels'=> array(
                        'required' => false,
                        'type'     => false,
                    ),
                ),
                'setUseDefaultRenderingStrategy' => array(
                    'useDefaultRenderingStrategy'=> array(
                        'required' => false,
                        'type'     => false,
                    ),
                ),
            ),
        )),
        'instance' => array(
            'alias' => array(
                'index' => 'Application\Controller\IndexController',
                'error' => 'Application\Controller\ErrorController',
            ),
            'Zend\Mvc\Controller\ActionController' => array(
                'parameters' => array(
                    'broker'       => 'Zend\Mvc\Controller\PluginBroker',
                ),
            ),
            'Zend\Mvc\Controller\PluginBroker' => array(
                'parameters' => array(
                    'loader' => 'Zend\Mvc\Controller\PluginLoader',
                ),
            ),
            
            
            'Zend\View\Resolver\TemplateMapResolver' => array('parameters' => array(
                'Zend\View\Resolver\TemplateMapResolver::__construct:map' => array(
                    'error'        => __DIR__ . '/../views/error/error.phtml',
                    'layout'       => __DIR__ . '/../views/layouts/layout.phtml',
                    'index/index'  => __DIR__ . '/../views/index/index.phtml',
                ),
            )),

            'Zend\View\PhpRenderer' => array(
                'parameters' => array(
                    'resolver' => 'Zend\View\Resolver\TemplateMapResolver',
                    'broker'   => 'Zend\View\HelperBroker',
                )
            ),

            'Zend\View\View' => array( 
                'parameters' => array(
                    'renderer' => 'Zend\View\PhpRenderer',
                )
            ),

            'Zend\Mvc\View\DefaultRenderingStrategy' => array(
                'parameters' => array(
                    'view' => 'Zend\View\View',
                )
            ),            
            
            
        ),
    ),
    'routes' => array(
        'default' => array(
            'type'    => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '/[:controller[/:action]]',
                'constraints' => array(
                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    'controller' => 'index',
                    'action'     => 'index',
                ),
            ),
        ),
        'home' => array(
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => array(
                'route'    => '/',
                'defaults' => array(
                    'controller' => 'index',
                    'action'     => 'index',
                ),
            ),
        ),
    ),
);
