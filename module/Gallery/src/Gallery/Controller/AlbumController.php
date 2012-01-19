<?php

namespace Gallery\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\Mvc\Router\RouteStack,
    ZfcUser\Service\User as UserService,
    Zend\Controller\Action\Helper\FlashMessenger,
    Gallery\Service\Photo as PhotoService;

class AlbumController extends ActionController
{
    protected $uploadForm;
    protected $authService;
    protected $photoService;

    public function indexAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }
        
        $event    = $this->getEvent();
        $matches  = $event->getRouteMatch();
        $displayname = $matches->getParam('displayname', '');

        if (empty($displayname)) {
            // redirect to /photos/{displayname}
            assert('test');
        }

        $images = $this->getPhotoService()->fetchLatestFor($displayname, 20);

        return array(
            'images' => $images,
        );
    }

    /**
     * @return \Gallery\Service\Photo
     */
    public function getPhotoService()
    {
        if (null === $this->photoService) {
            $this->photoService = $this->getLocator()->get('gallery_photo_service');
        }
        return $this->photoService;
    }

}
