<?php


namespace Application\View;

use Zend\EventManager\EventCollection,
    Zend\EventManager\ListenerAggregate,
    Zend\Http\Request as HttpRequest,
    Zend\Http\Response as HttpResponse,
    Zend\Mvc\Application,
    Zend\Mvc\MvcEvent,
    Zend\View\Model as ViewModel,
    Zend\View\PhpRenderer,
    Zend\View\Renderer\FeedRenderer,
    Zend\View\Renderer\JsonRenderer,
    Zend\View\View,
    Zend\View\ViewEvent,
    Zend\Mvc\View\DefaultRenderingStrategy;

/**
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class RenderingStrategy extends DefaultRenderingStrategy
{
    
    /**
     * Render the view
     * 
     * @param  MvcEvent $e 
     * @return \Zend\Stdlib\ResponseDescription
     */
    public function render($e)
    {

        $request = $e->getRequest();
        $basePath = $request->getBaseUrl();

        $renderer = $this->view->getRenderer('Zend\View\PhpRenderer');
        $renderer->plugin('basePath')->setBasePath($basePath);
        $renderer->plugin('headLink')->appendStylesheet($basePath . '/css/bootstrap.min.css');
        $renderer->plugin('headLink')->appendStylesheet($basePath . '/css/site.css');

        $html5js = '<script src="' . $basePath . 'js/html5.js"></script>';
        $renderer->plugin('placeHolder')->__invoke('html5js')->set($html5js);
        
        $renderer->headLink(array(
            'rel'  => 'shortcut icon',
            'type' => 'image/vnd.microsoft.icon',
            'href' => $basePath . '/images/favicon.ico',
        ));  

        $renderer->doctype('HTML5');
        $renderer->headTitle()->setSeparator(' - ')
                              ->setAutoEscape(false);

        return parent::render($e);
    }
    
}


