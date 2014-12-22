<?php
namespace Jme\MediaBundle\Service;

use Jme\MediaBundle\Repository\MediaRepository;
use Jme\MediaBundle\Helper\UploadHelper;
use Symfony\Component\Form\Form;

use Xi\Filelib\FileLibrary;
use Xi\Filelib\Publisher\Publisher;

class MediaService 
{
    /**
     * @var \Xi\Filelib\FileLibrary
     */
    private $filelib;

    /**
     * @var MediaRepository
     */
    private $mediaRepository;

    /**
     * @var \Xi\Filelib\Publisher\Publisher
     */
    private $publisher;

    /**
     * @param FileLibrary $filelib
     * @param MediaRepository $mediaRepository
     * @param Publisher $publisher
     */
    public function __construct(FileLibrary $filelib, MediaRepository $mediaRepository, Publisher $publisher)
    {
        $this->filelib          = $filelib;
        $this->mediaRepository  = $mediaRepository;
        $this->publisher        = $publisher;
    }

    /**
     * @param Form $form
     */
    public function saveByForm(Form $form)
    {
        $media = $form->getData();
        $file = $this->filelib->uploadFile($media->getFile()->getPathName());

        $this->publisher->publishAllVersions($file);
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->mediaRepository->getImages();
    }
}
 