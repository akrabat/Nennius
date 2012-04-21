<?php

namespace Application\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\Stdlib\RequestDescription as Request,
    Zend\Stdlib\ResponseDescription as Response,
    Zend\View\Model\ViewModel;

class IndexController extends ActionController
{
    protected $photoService;

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
