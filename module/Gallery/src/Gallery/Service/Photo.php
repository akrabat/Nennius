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

        $class = Module::getOption('photo_model_class');
        $photo = new $class;
        $photo->setTitle($form->getValue('title'));
        $photo->setDescription($form->getValue('description'));

        $this->events()->trigger(__FUNCTION__, $this, array('photo' => $photo, 'form' => $form));
        $this->photoMapper->persist($photo);

        // retrieve file

        $filePath = realpath(Module::getOption('file_path')) . '/' . $photo->getId();
        if (!is_dir($filePath)) {
            mkdir($filePath);
        }
        $filename = substr(md5(microtime() . mt_rand()),0,10) . '.' . pathinfo($form->file->getValue(), PATHINFO_EXTENSION);
        $form->file->setDestination($filePath);
        $form->file->addFilter('Rename', $filename);
        $form->file->setOptions(array('useByteString' => false));
        $photo->setFilename($form->file->getValue());

        if (!$form->file->receive()) {
            print "Upload error";
        }
        $info = $form->file->getFileInfo();
        $fullFilePath = $form->file->getFileName();

        $photo->setFilenameOnDisk($filename);
        $photo->setMimeType($form->file->getMimeType());
        $photo->setSize($form->file->getFileSize());

        $imageSize = getimagesize($fullFilePath);
        $photo->setWidth($imageSize[0]);
        $photo->setHeight($imageSize[1]);

        $this->photoMapper->persist($photo);

        return $photo;
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
