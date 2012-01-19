<?php

namespace Application\Controller;

use Zend\Mvc\Controller\ActionController;

class IndexController extends ActionController
{
    protected $photoService;

    public function indexAction()
    {
        // Rather than use DI, let's use the Service locator this time
        /* @var \Gallery\Photo\Mapper $mapper */
        $mapper = $this->getLocator()->get('gallery_photo_mapper');
        $latest = $mapper->fetchLatestFor(null);
        return array('latest' => $latest);
    }
}
