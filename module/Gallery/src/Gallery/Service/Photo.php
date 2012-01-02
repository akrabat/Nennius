<?php

namespace Gallery\Service;

use Gallery\Form\Upload as UploadForm,
    Gallery\Module,
    Gallery\Model\PhotoMapper,
    Zend\EventManager\EventCollection,
    Zend\EventManager\EventManager;

class Photo
{
   /**
     * userMapper
     *
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var EventCollection
     */
    protected $events;

   /**
     * createFromForm
     *
     * @param UploadForm $form
     * @return Gallery\Entity\Photo
     */
    public function createFromForm(UploadForm $form)
    {
        $uploadedData = $form->getValues();
        LDBG($uploadedData, 'uploadedData');
        $class = Module::getOption('photo_model_class');
        $photo = new $class;
        $photo->setTitle($form->getValue('title'));
        $photo->setDescription($form->getValue('description'));

        LDBG($photo, 'photo');

        $this->events()->trigger(__FUNCTION__, $this, array('photo' => $photo, 'form' => $form));
        $this->photoMapper->persist($photo);
exit;
        // retrieve file
        if (!$form->file->receive()) {
            print "Upload error";
        }

        $fullFilePath = $form->file->getFileName();
        LDBG($fullFilePath);


        exit;

        $user = new $class;
        $user->setEmail($form->getValue('email'))
        ->setPassword($this->hashPassword($form->getValue('password')))
        ->setRegisterIp($_SERVER['REMOTE_ADDR'])
        ->setRegisterTime(new DateTime('now'))
        ->setEnabled(true);
        if (Module::getOption('require_activation')) {
            $user->setActive(false);
        } else {
            $user->setActive(true);
        }
        if (Module::getOption('enable_username')) {
            $user->setUsername($form->getValue('username'));
        }
        if (Module::getOption('enable_display_name')) {
            $user->setDisplayName($form->getValue('display_name'));
        }
        $this->events()->trigger(__FUNCTION__, $this, array('user' => $user, 'form' => $form));
        $this->userMapper->persist($user);
        return $user;
    }

    /**
     *
     * @param PhotoMapper $photoMapper
     * @return Photo
     */
    public function setUserMapper(PhotoMapper $photoMapper)
    {
        $this->photoMapper = $photoMapper;
        return $this;
    }

    /**
     * Set the event manager instance used by this context
     *
     * @param  EventCollection $events
     * @return mixed
     */
    public function setEventManager(EventCollection $events)
    {
        $this->events = $events;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventCollection
     */
    public function events()
    {
        if (!$this->events instanceof EventCollection) {
            $identifiers = array(__CLASS__, get_class($this));
            if (isset($this->eventIdentifier)) {
                if ((is_string($this->eventIdentifier))
                    || (is_array($this->eventIdentifier))
                    || ($this->eventIdentifier instanceof Traversable)
                ) {
                    $identifiers = array_unique($identifiers + (array) $this->eventIdentifier);
                } elseif (is_object($this->eventIdentifier)) {
                    $identifiers[] = $this->eventIdentifier;
                }
                // silently ignore invalid eventIdentifier types
            }
            $this->setEventManager(new EventManager($identifiers));
        }
        return $this->events;
    }
}
