<?php


namespace Application\View;

use Zend\Mvc\View\DefaultRenderingStrategy;

/**
 * Specific rendering strategy for this application
 */
class RenderingStrategy extends DefaultRenderingStrategy
{
    protected $defaultLayout = 'layouts/layout';
    
    /**
     * Render the view
     * 
     * @param  MvcEvent $e 
     * @return \Zend\Stdlib\ResponseDescription
     */
    public function render($e)
    {
        $request = $e->getRequest();

        $renderer = $this->view->getRenderer('Zend\View\PhpRenderer');
        $renderer->plugin('basePath')->setBasePath($request->getBaseUrl());

        $renderer->doctype('HTML5');
        return parent::render($e);
    }
    
}
