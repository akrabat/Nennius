<?php

namespace Gallery\Service;

use Gallery\Form\Upload as UploadForm,
    Gallery\Module,
    Gallery\Model\PhotoMapper,
    Gallery\Model\PhotoInterface,
    Zend\EventManager\EventCollection,
    ZfcUser\Model\User,
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

    protected function filePath(PhotoInterface $photo, $includeFilename = true) 
    {
        $filePath = realpath(Module::getOption('file_path'));
        $filePath .= '/' . $photo->getCreatedBy();

        if ($includeFilename) {
            $filePath .= '/' . $photo->getFilenameOnDisk();
        }

        return $filePath;
    }

    /**
     * createFromForm
     *
     * @param UploadForm $form
     * @return Gallery\Entity\Photo
     */
    public function createFromForm(UploadForm $form, User $user)
    {
        $values = $form->getValues();

        // create entity
        $class = Module::getOption('photo_model_class');
        $photo = new $class;
        $photo->setTitle($values['title']);
        $photo->setDescription($values['description']);
        $photo->setCreatedBy($user->getUserId());
        $photo->setFilename($values['file']);
        $photo->setFilenameSalt(sha1(mt_rand(1, 4294967296).microtime()));

        // retrieve file
        $filePath = $this->filePath($photo, false);
        if (!is_dir($filePath)) {
            mkdir($filePath, 0777, true);
        }
        $form->file->setDestination($filePath);

        $filename = md5($values['file']) . '.' . pathinfo($values['file'], PATHINFO_EXTENSION);
        $form->file->addFilter('Rename', $filename);
        $form->file->setOptions(array('useByteString' => false));

        if (!$form->file->receive()) {
            throw new Exception("Upload error");
        }

        $photo->setFilenameOnDisk($filename);
        $photo->setMimeType($form->file->getMimeType());
        $photo->setSize($form->file->getFileSize());

        $imageSize = getimagesize($this->filePath($photo));
        $photo->setWidth($imageSize[0]);
        $photo->setHeight($imageSize[1]);

        // store entity
        $this->events()->trigger(__FUNCTION__.'.pre', $this, array('photo' => $photo, 'form' => $form));
        $this->photoMapper->persist($photo);
        $this->events()->trigger(__FUNCTION__.'.post', $this, array('photo' => $photo, 'form' => $form));

        return $photo;
    }

    public function fetchLatestFor($displayname, $count)
    {
        return $this->photoMapper->fetchLatestFor($displayname, $count);
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
