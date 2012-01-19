<?php

namespace Gallery\Controller;

use Zend\Mvc\Controller\ActionController,
    Zend\Mvc\Router\RouteStack,
    ZfcUser\Service\User as UserService,
    Zend\Controller\Action\Helper\FlashMessenger,
    Gallery\Service\Photo as PhotoService;

class UploadController extends ActionController
{
    protected $uploadForm;
    protected $photoService;

    public function indexAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcuser/login');
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
                $this->getPhotoService()->createFromForm($form, $this->zfcUserAuthentication()->getIdentity());
                return $this->redirect()->toRoute('photos');
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

    public function getPhotoService()
    {
        if (null === $this->photoService) {
            $this->photoService = $this->getLocator()->get('gallery_photo_service');
        }
        return $this->photoService;
    }

}
