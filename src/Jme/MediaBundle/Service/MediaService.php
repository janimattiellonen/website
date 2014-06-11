<?php
namespace Jme\MediaBundle\Service;

use Jme\MediaBundle\Repository\MediaRepository;
use Symfony\Component\Form\Form;

use Xi\Filelib\FileLibrary;

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
     * @param FileLibrary $filelib
     * @param MediaRepository $mediaRepository
     */
    public function __construct(FileLibrary $filelib, MediaRepository $mediaRepository)
    {
        $this->filelib          = $filelib;
        $this->mediaRepository  = $mediaRepository;
    }

    /**
     * @param Form $form
     */
    public function saveByForm(Form $form)
    {
        $uploadHelper = new \Jme\MediaBundle\Helper\UploadHelper();

        $media = $form->getData();

        $fileUpload = $uploadHelper->createFileUpload($media->getFile());

        $this->filelib->upload($fileUpload, null, 'image_question');
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->mediaRepository->getImages();
    }
}
 