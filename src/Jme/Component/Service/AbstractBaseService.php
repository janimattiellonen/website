<?php
namespace Jme\Component\Service;

use Doctrine\ORM\EntityManager;

abstract class AbstractBaseService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    public function beginTransaction()
    {
        $this->em->getConnection()->beginTransaction();
    }

    public function commitTransaction()
    {
        $this->em->getConnection()->commit();
    }

    public function rollBackTransaction()
    {
        $this->em->getConnection()->rollBack();
    }
}
