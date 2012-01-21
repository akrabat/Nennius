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
    
    public function createThumbnail(PhotoInterface $photo, $width, $height, $type='bounding')
    {
        $width = ($width > 0) ? $width : $photo->getWidth();
        $height = ($height > 0) ? $height : $photo->getHeight();

        $dimensions = $this->calculateTrueSize($photo, $width, $height, $type);
        $width = $dimensions['width'];
        $height = $dimensions['height'];

        $thumbnailFilename = $this->thumbnailFilename($photo, $width, $height, $type);
        if (file_exists($thumbnailFilename)) {
            return array(
                'url' => $this->thumbnailUrl($photo, $width, $height, $type),
                'width' => $width,
                'height' => $height,
                );

        }

        $filename = $this->filePath($photo);
        if ($photo->getWidth() == $width && $photo->getHeight() == $height) {
            // image is the same size, so overide with the original image
            copy($filename, $thumbnailFilename);
            return array(
                'url' => $this->thumbnailUrl($photo, $width, $height, $type),
                'width' => $width,
                'height' => $height,
                );
        }

        // not the same size, create correct image
        switch ($photo->getMimeType()) {
            case 'image/jpeg':
                $srcResource = imagecreatefromjpeg($filename);
                break;

            case 'image/gif':
                $srcResource = imagecreatefromgif($filename);
                break;

            case 'image/png':
                $srcResource = imagecreatefrompng($filename);
                break;
            
            default:
                throw new Exception('Unsupported mime type');
        }
        if (!$srcResource) {
            throw new Exception('Failed to create image');
        }
                
        switch ($type)
        {
            case 'bounding':
                $destResource = $this->createBoundingThumbnail($photo, $srcResource, $width, $height);
                break;
                
            case 'square':
                $destResource = $this->createSquareThumbnail($photo, $srcResource, $width);
                break;
        }

        switch ($photo->getMimeType()) {
            case 'image/jpeg':
                imagejpeg($destResource, $thumbnailFilename, 100);
                break;

            case 'image/gif':
                imagecolortransparent($destResource);
                imagegif($destResource, $thumbnailFilename);
                break;

            case 'image/png':
                imagepng($destResource, $thumbnailFilename);
                break;
            
            default:
                throw new Exception('Unsupported mime type');
        }

        imagedestroy($srcResource);
        imagedestroy($destResource);

        return array(
            'url' => $this->thumbnailUrl($photo, $width, $height, $type),
            'width' => $width,
            'height' => $height,
        );
        
    }

    public function calculateTrueSize(PhotoInterface $photo, $width, $height, $type)
    {
        $newWidth = $width;
        $newHeight = $height;

        switch ($type) {
            case 'square':
                $newWidth = $width;
                $newHeight = $height;
                break;
            
            case 'bounding':
                if ($photo->getWidth() < $width && $photo->getHeight() < $height) {
                    $newWidth = $photo->getWidth();
                    $newHeight = $photo->getHeight();
                } else {
                    $newWidth = $width;
                    $newHeight = intval($photo->getHeight() * $width / $photo->getWidth());
                    if ($height > 0 && $newHeight > $height)
                    {
                        $newHeight = $height;
                        $newWidth = intval($photo->getWidth() * $height / $photo->getHeight());
                    }
                }
                break;
        }

        return array(
            'width' => $newWidth,
            'height' => $newHeight,
            );
    }

    protected function createBoundingThumbnail(PhotoInterface $photo, $srcResource, $width, $height)
    {
        switch ($photo->getMimeType()) {
            case 'image/jpeg':
                $destResource = imagecreatetruecolor($width, $height);
                imagecopyresampled($destResource, $srcResource, 0, 0, 0, 0, 
                    $width, $height, $photo->getWidth(), $photo->getHeight());
                break;

            default:
                $destResource = imagecreate($width, $height);
                imagecopyresized($destResource, $srcResource, 0, 0, 0, 0, 
                    $width, $height, $photo->getWidth(), $photo->getHeight());
        }
        
        return $destResource;
    }

    protected function createSquareThumbnail(PhotoInterface $photo, $srcResource, $size)
    {
        $largestSquareSize = $photo->getWidth();
        if($photo->getWidth() > $photo->getHeight()) {
            $largestSquareSize = $photo->getHeight();
        }

        $offset_x = (int)(($photo->getWidth() - $largestSquareSize) / 2);
        $offset_y = (int)(($photo->getHeight() - $largestSquareSize) / 2);
        
        switch ($photo->getMimeType()) {
            case 'image/jpeg':
                $destResource = imagecreatetruecolor($size, $size);
                imagecopyresampled($destResource, $srcResource, 0, 0, $offset_x, $offset_y, 
                    $size, $size, $largestSquareSize, $largestSquareSize);
                break;

            default:
                $destResource = imagecreate($largestSquareSize, $largestSquareSize);
                imagecopyresized($destResource, $srcResource, 0, 0, $offset_x, $offset_y, 
                    $size, $size, $largestSquareSize, $largestSquareSize);
        }
        
        return $destResource;
    }



    protected function thumbnailFilename($photo, $width, $height, $type)
    {
        $filePath = realpath(Module::getOption('public_file_path'))
                  . '/' . $photo->getCreatedBy()
                  . '/' . $photo->getId();

        if (!is_dir($filePath)) {
            mkdir($filePath, 0777, true);
        }

        $filePath .= '/' . sha1($photo->getFilenameSalt() . $width . $height . $type)
                  .  '.' . $photo->getFileExtension();

        return $filePath;
    }

    protected function thumbnailUrl($photo, $width, $height, $type)
    {
        $url = Module::getOption('public_file_url')
            . '/' . $photo->getCreatedBy()
            . '/' . $photo->getId()
            . '/' . sha1($photo->getFilenameSalt() . $width . $height . $type)
            . '.' . $photo->getFileExtension();

        return $url;
    }    

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
        $photo->setFilenameSalt(sha1(mt_rand().microtime()));

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
