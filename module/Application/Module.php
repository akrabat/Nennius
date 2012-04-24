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
        $sharedEvents = $moduleManager->events()->getSharedCollections();
        $sharedEvents->attach('bootstrap', 'bootstrap', array($this, 'initializeView'), 100);
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
        $application  = $e->getParam('application');
        $basePath     = $application->getRequest()->getBasePath();
        $locator      = $application->getLocator();
        $renderer     = $locator->get('Zend\View\Renderer\PhpRenderer');

        $renderer->doctype('HTML5');
        $renderer->plugin('url')->setRouter($application->getRouter());
        $renderer->doctype()->setDoctype('HTML5');
        $renderer->plugin('basePath')->setBasePath($basePath);

    }

}
