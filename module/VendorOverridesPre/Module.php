<?php

namespace VendorOverridesPre;

use Zend\Module\Consumer\AutoloaderProvider,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Manager;

class Module implements AutoloaderProvider
{
    public function init(Manager $moduleManager)
    {
    }

    public function getAutoloaderConfig()
    {
        return array();
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
