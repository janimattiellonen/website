<?php
namespace Jme\ArticleBundle\Repository;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Jme\ArticleBundle\Entity\Article;

class ArticleRepository extends EntityRepository
{
    /**
     * @param $amount
     * @return array
     */
    public function fetchLatestArticles($amount)
    {
        $qb = $this->getBaseQueryBuilder();
        $qb->setMaxResults($amount);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    protected function getBaseQueryBuilder()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('a')
            ->from('Jme\ArticleBundle\Entity\Article', 'a');
    }
}
