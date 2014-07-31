<?php
namespace Jme\MediaBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MediaRepository extends EntityRepository
{
    /**
     * @return mixed
     */
    public function getImages()
    {
        $qb = $this->getBaseQueryBuilder();

        $mimetypes = [
            'image/jpeg',
            'image/gif',
            'image/png',
        ];

        $qb->join('f.resource', 'r');

        $qb->where('r.mimetype = :mimetypes')
            ->orderBy('f.dateCreated', 'DESC')
            ->setParameter('mimetypes', 'image/jpeg');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    protected function getBaseQueryBuilder()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('f')
            ->from('Xi\Filelib\Backend\Adapter\DoctrineOrm\Entity\File', 'f');
    }
}
 