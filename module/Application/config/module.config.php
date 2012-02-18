<?php
return array(
    'display_exceptions' => true,
    
    'di' => array(

        'definition' => array(
            'class' => array(
                'Zend\View\Renderer\PhpRenderer' => array(
                    'setResolver' => array(
                        'resolver' => array(
                            'required' => false,
                            'type'     => 'Zend\View\Resolver',
                        ),
                    ),
                ),
            ),
        ),

        'instance' => array(
            'alias' => array(
                'index' => 'Application\Controller\IndexController',
                'error' => 'Application\Controller\ErrorController',
            ),

            // Inject the plugin broker for controller plugins into
            // the action controller for use by all controllers that
            // extend it.
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

            // Set up the view layer.
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'options'  => array(
                        'script_paths' => array(
                            'application' => __DIR__ . '/../view',
                        ),
                    ),
                ),
            ),
            'Zend\View\Renderer\PhpRenderer' => array(
                'parameters' => array(
                    'renderTrees' => false,
                    'resolver' => 'Zend\View\Resolver\TemplatePathStack',
                    'broker'   => 'Zend\View\HelperBroker',
                ),
            ),
            'Zend\Mvc\View\DefaultRenderingStrategy' => array(
                'parameters' => array(
                    'baseTemplate' => 'layout/layout.phtml',
                ),
            ),
            'Zend\Mvc\View\ExceptionStrategy' => array(
                'parameters' => array(
                    'displayExceptions' => true,
                    'template'     => 'error/index.phtml',
                ),
            ),
            'Zend\Mvc\View\RouteNotFoundStrategy' => array(
                'parameters' => array(
                    'notFoundTemplate' => 'error/404.phtml',
                ),
            ),

            // Setup the router and routes
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
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
                ),
            ),
            
        ),
    ),

);
