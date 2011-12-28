<?php

namespace VendorOverride;

use Zend\Module\Consumer\AutoloaderProvider,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Manager;

class Module implements AutoloaderProvider
{
    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();

        // Override EdpUser to move the display name field to the top
        $events->attach('EdpUser\Form\Register', 'init', function($e) {
            $form = $e->getTarget();
            $displayName = $form->getElement('display_name');
            $displayName->setOrder(10);
            $form->addElement($displayName, 'display_name');
        });
    }

    public function getAutoloaderConfig()
    {
        return array();
    }

    // public function getAutoloaderConfig()
    // {
    //     return array(
    //         'Zend\Loader\ClassMapAutoloader' => array(
    //             __DIR__ . '/autoload_classmap.php',
    //         ),
    //         'Zend\Loader\StandardAutoloader' => array(
    //             'namespaces' => array(
    //                 __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
    //             ),
    //         ),
    //     );
    // }

    // public function getConfig()
    // {
    //     return include __DIR__ . '/config/module.config.php';
    // }
}
