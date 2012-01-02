<?php

namespace Gallery;

use Zend\Module\Consumer\AutoloaderProvider
    ,Zend\Module\Manager;

class Module implements AutoloaderProvider
{
    protected static $options;

    public function init(Manager $moduleManager)
    {
        $moduleManager->events()->attach('loadModules.post', array($this, 'modulesLoaded'));
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @TODO: Come up with a better way of handling module settings/options
     */
    public function modulesLoaded($e)
    {
        $config = $e->getConfigListener()->getMergedConfig();
        static::$options = $config['gallery'];
    }

    public static function getOption($option)
    {
        if (!isset(static::$options[$option])) {
            return null;
        }
        return static::$options[$option];
    }
}
