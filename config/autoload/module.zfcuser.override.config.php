<?php

return array(
    'di' => array(
        'instance' => array(

           'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
                    'routes' => array(
                        'zfcuser' => array(
                            'options' => array(
                                'route' => '/member',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),    
);
