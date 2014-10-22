<?php
namespace Jme\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Xi\Filelib\Backend\Adapter\DoctrineOrm\Entity\File as BaseFile;


/**
 * @ORM\Entity(repositoryClass="Jme\MediaBundle\Repository\MediaRepository")
 */
class File extends BaseFile
{

}
 