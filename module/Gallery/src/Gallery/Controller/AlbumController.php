<?php

namespace Gallery\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\Mvc\Router\RouteStack,
    EdpUser\Service\User as UserService,
    Zend\Controller\Action\Helper\FlashMessenger,
    Gallery\Service\Photo as PhotoService;

class AlbumController extends ActionController
{
    protected $uploadForm;
    protected $authService;
    protected $photoService;

    public function indexAction()
    {
        if (!$this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('edpuser/login');
        }
        $event    = $this->getEvent();
        $matches  = $event->getRouteMatch();
        $username = $matches->getParam('username', false);
        if (!$username) {
            var_dump($this->getAuthService()->getIdentity());
        }


        $images = array();
        return array(
            'images' => $images,
        );
    }

    public function getAuthService()
    {
        if (null === $this->authService) {
            $userService = $this->getLocator()->get('edpuser_user_service');
            $this->authService = $userService->getAuthService();
        }
        return $this->authService;
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
