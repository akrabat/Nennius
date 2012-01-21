<?php

namespace Gallery\Model;

use DateTime,
    ZfcBase\Model\ModelAbstract;

class Photo extends ModelAbstract implements PhotoInterface
{
    protected $id;
    protected $title;
    protected $description;
    protected $filename;
    protected $mime_type;
    protected $size;
    protected $width;
    protected $height;
    protected $filename_on_disk;
    protected $filename_salt;
    protected $created_by;
    protected $date_created;

    public function getFileExtension()
    {
        return pathinfo($this->getFilename(), PATHINFO_EXTENSION);
    }

    // -- getters and setters --
    
    /*
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id the value to be set
     * @return Photo
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /*
     * @return int $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title the value to be set
     * @return Photo
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /*
     * @return int $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description the value to be set
     * @return Photo
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /*
     * @return int $filename
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename the value to be set
     * @return Photo
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /*
     * @return int $mimeType
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * @param string $mimeType the value to be set
     * @return Photo
     */
    public function setMimeType($mimeType)
    {
        if ($mimeType == 'image/pjpeg') {
            $mimeType = 'image/jpeg';
        }
        if ($mimeType == 'image/x-png') {
            $mimeType = 'image/png';
        }
        $this->mime_type = $mimeType;
        return $this;
    }

    /*
     * @return int $size
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size the value to be set
     * @return Photo
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /*
     * @return int $width
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width the value to be set
     * @return Photo
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /*
     * @return int $height
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height the value to be set
     * @return Photo
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }
    
    /*
     * @return string $filenameOnDisk
     */
    public function getFilenameOnDisk()
    {
        return $this->filename_on_disk;
    }

    /**
     * @param string $filenameOnDisk the value to be set
     * @return Photo
     */
    public function setFilenameOnDisk($filenameOnDisk)
    {
        $this->filename_on_disk = $filenameOnDisk;
        return $this;
    }
    
    /*
     * @return string $filenameSalt
     */
    public function getFilenameSalt()
    {
        return $this->filename_salt;
    }

    /**
     * @param string $filenameSalt the value to be set
     * @return Photo
     */
    public function setFilenameSalt($filenameSalt)
    {
        $this->filename_salt = $filenameSalt;
        return $this;
    }
    
    /*
     * @return string $createdBy
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * @param string $createdBy the value to be set
     * @return Photo
     */
    public function setCreatedBy($createdBy)
    {
        $this->created_by = $createdBy;
        return $this;
    }
        
    /*
     * @return DateTime $dateCreated
     */
    public function getDateCreated()
    {
        if (is_null($this->date_created)) {
            $this->date_created = new DateTime();
        }
        return $this->date_created;
    }

    /**
     * @param mixed $dateCreated the value to be set
     * @return Photo
     */
    public function setDateCreated($dateCreated)
    {
        if ($dateCreated instanceof DateTime) {
            $this->date_created = $dateCreated;
        } else {
            $this->date_created = new DateTime($dateCreated);
        }
        return $this;
    }
}
