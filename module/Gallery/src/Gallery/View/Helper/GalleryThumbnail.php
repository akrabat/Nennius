<?php

namespace Gallery\View\Helper;

use Zend\View\Helper\AbstractHelper,
    Gallery\Model\Photo,
    Gallery\Service\Photo as PhotoService;

class GalleryThumbnail extends AbstractHelper
{
    /**
     * @var PhotoService
     */
    protected $photoService;

    /**
     * __invoke 
     * 
     * @access public
     * @return string
     */
    public function __invoke(Photo $photo, $width, $height, $type='bounding', $attribs=array())
    {
        $view = $this->getView();
        

        $service = $this->getPhotoService();
        $thumbnail = $service->createThumbnail($photo, $width, $height, $type);

        if (!$thumbnail) {
            return;
        }

        $url = $view->basePath() . '/' . $thumbnail['url'];
        $width = $thumbnail['width'];
        $height = $thumbnail['height'];

        $html = '<img src="' . $url . '" width="'.$width.'" height="'.$height.'"';

        if ($view->doctype()->isXhtml()) {
            $html .= '/';
        }
        $html .= '>';

        return $html;
    }

    /**
     * Get PhotoService.
     *
     * @return AuthenticationService
     */
    public function getPhotoService()
    {
        return $this->photoService;
    }
 
    /**
     * Set PhotoService.
     *
     * @param PhotoService $photoService
     */
    public function setPhotoService(PhotoService $photoService)
    {
        $this->photoService = $photoService;
        return $this;
    }
}
