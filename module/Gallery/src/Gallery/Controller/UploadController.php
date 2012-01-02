<?php

namespace Gallery\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\Mvc\Router\RouteStack,
    EdpUser\Service\User as UserService,
    Zend\Controller\Action\Helper\FlashMessenger,
    Gallery\Service\Photo as PhotoService;

class UploadController extends ActionController
{
    protected $uploadForm;
    protected $authService;
    protected $photoService;

    public function indexAction()
    {
        if (!$this->getAuthService()->hasIdentity()) {
            return $this->redirect()->toRoute('edpuser/login');
        }
        $request = $this->getRequest();
        $form = $this->getUploadForm();
        if ($request->isPost()) {
            if (false === $form->isValid($request->post()->toArray())) {
                echo "Form validation failure!";
                LDBG($form->getMessages());
                LDBG($request->post()->toArray());exit;
                $this->flashMessenger()->setNamespace('gallery-upload-form')->addMessage($request->post()->toArray());
                return $this->redirect()->toRoute('photos/upload');
            } else {
                $this->getPhotoService()->createFromForm($form);
                if (Module::getOption('login_after_registration')) {
                    $auth = $this->getUserService()->authenticate($request->post()->get('email'), $request->post()->get('password'));
                    if (false !== $auth) {
                        return $this->redirect()->toRoute('edpuser');
                    }
                }
                return $this->redirect()->toRoute('photos/');
            }
        }
        return array(
            'uploadForm' => $form,
        );
    }


    public function getUploadForm()
    {
        if (null === $this->uploadForm) {
            $this->uploadForm = $this->getLocator()->get('gallery_upload_form');
            $fm = $this->flashMessenger()->setNamespace('gallery-upload-form')->getMessages();
            if (isset($fm[0])) {
                $this->uploadForm->addErrorMessage($fm[0]);
            }
        }
        return $this->uploadForm;
    }

    public function getAuthService()
    {
        if (null === $this->authService) {
            $userService = $this->getLocator()->get('edpuser_user_service');
            $this->authService = $userService->getAuthService();
        }
        return $this->authService;
    }

    public function getPhotoService()
    {
        if (null === $this->photoService) {
            $this->photoService = $this->getLocator()->get('gallery_photo_service');
        }
        return $this->photoService;
    }

}
