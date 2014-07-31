<?php
namespace Jme\MediaBundle\Helper;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Xi\Filelib\File\Upload\FileUpload;

class UploadHelper
{
    /**
     * @param UploadedFile $file
     * @return FileUpload
     */
    public function createFileUpload(UploadedFile $file)
    {
        $fileUpload = new FileUpload($file->getPathname());

        return $fileUpload;
    }
}
 