<?php

namespace Application;

use Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider
{
    protected $view;
    protected $viewListener;

    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'initializeView'), 100);
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

    public function initializeView($e)
    {
        $app     = $e->getParam('application');
        $config  = $e->getParam('config');
        $locator = $app->getLocator();

        // Get and attach view listener
        // $listener = $locator->get('Zend\Mvc\View\DefaultRenderingStrategy');
        $listener = $locator->get('Application\View\RenderingStrategy');
        $app->events()->attachAggregate($listener);

        // Ensure PhpRenderer is properly setup
        $router   = $app->getRouter();
        $view     = $locator->get('Zend\View\View');
        $renderer = $locator->get('Zend\View\PhpRenderer');
        $view->addRenderer($renderer);

        $url = $renderer->plugin('url');
        $url->setRouter($router);

        $persistent = $renderer->placeholder('layout');
        foreach ($config->view as $var => $value) {
            if ($value instanceof Config) {
                $value = new Config($value->toArray(), true);
            }
            $persistent->{$var} = $value;
        }

    }

}
