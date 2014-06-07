<?php
namespace Jme\MediaBundle\Service;

use Symfony\Component\Form\Form;

use Xi\Filelib\FileLibrary;

class MediaService 
{
    /**
     * @var \Xi\Filelib\FileLibrary
     */
    private $filelib;

    /**
     * @param FileLibrary $filelib
     */
    public function __construct(FileLibrary $filelib)
    {
        $this->filelib = $filelib;
    }

    /**
     * @param Form $form
     */
    public function saveByForm(Form $form)
    {
        $uploadHelper = new \Jme\MediaBundle\Helper\UploadHelper();

        $media = $form->getData();

        $fileUpload = $uploadHelper->createFileUpload($media->getFile());

        $this->filelib->upload($fileUpload);


    }
}
 