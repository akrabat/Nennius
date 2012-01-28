<?php

namespace Application\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\Stdlib\RequestDescription as Request,
    Zend\Stdlib\ResponseDescription as Response,
    Zend\View\Model\ViewModel;

class IndexController extends ActionController
{
    protected $photoService;
    
    /*
     * One option. Overide dispatch in order to set up the basePath related stuff.
     */
    /*
    public function dispatch(Request $request, Response $response = null)
    {
        $renderer = $this->getLocator()->get('Zend\View\PhpRenderer');

        $basePath = $request->getBaseUrl();

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
                              ->setAutoEscape(false)
                              ->append('Nennius');


        return parent::dispatch($request, $response);
    }
    */

    public function indexAction()
    {
        // Rather than use DI, let's use the Service locator this time
        /* @var \Gallery\Photo\Mapper $mapper */
        $mapper = $this->getLocator()->get('gallery_photo_mapper');
        $latest = $mapper->fetchLatestFor(null);

        
        $result = new ViewModel();
        $result->setVariables(array(
            'latest'     => $latest,
        ));
        $result->setOptions(array(
            'template'   => 'index/index',
            'use_layout' => true,
        ));
        return $result;
    }
}
