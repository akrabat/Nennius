<?php

namespace Gallery\Model;

interface PhotoInterface
{
    /*
     * @return int id
     */
    public function getId();

    /**
     * @param string $id the value to be set
     * @return Photo
     */
    public function setId($id);

    /*
     * @return int title
     */
    public function getTitle();

    /**
     * @param string $title the value to be set
     * @return Photo
     */
    public function setTitle($title);

    /*
     * @return int description
     */
    public function getDescription();

    /**
     * @param string $description the value to be set
     * @return Photo
     */
    public function setDescription($description);

    /*
     * @return int filename
     */
    public function getFilename();

    /**
     * @param string $filename the value to be set
     * @return Photo
     */
    public function setFilename($filename);

    /*
     * @return int mimeType
     */
    public function getMimeType();

    /**
     * @param string $mimeType the value to be set
     * @return Photo
     */
    public function setMimeType($mimeType);

    /*
     * @return int size
     */
    public function getSize();

    /**
     * @param int $size the value to be set
     * @return Photo
     */
    public function setSize($size);

    /*
     * @return int width
     */
    public function getWidth();

    /**
     * @param int $width the value to be set
     * @return Photo
     */
    public function setWidth($width);

    /*
     * @return int height
     */
    public function getHeight();

    /**
     * @param int $height the value to be set
     * @return Photo
     */
    public function setHeight($height);

    /*
     * @return DateTime dateCreated
     */
    public function getDateCreated();

    /**
     * @param mixed $dateCreated the value to be set
     * @return Photo
     */
    public function setDateCreated($dateCreated);

   /**
     * Convert a model class to an array recursively 
     * 
     * @param mixed $array 
     * @return array
     */
    public function toArray($array = false);

    /**
     * Convert an array to an instance of a model class 
     * 
     * @param array $array 
     * @return Edp\Common\Model
     */
    public static function fromArray($array);

}