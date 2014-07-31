<?php
namespace Jme\ArticleBundle\Repository;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Jme\ArticleBundle\Entity\Article;
use Jme\ArticleBundle\Service\Exception\ArticleNotFoundException;

class ArticleRepository extends EntityRepository
{
    /**
     * @param $amount
     * @param boolean $isAdmin
     *
     * @return array
     */
    public function fetchLatestArticles($amount, $isAdmin = false)
    {
        $qb = $this->getBaseQueryBuilder();

        if (!$isAdmin) {
            $qb->andWhere('a.published = 1');
        }

        $qb->setMaxResults($amount);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     *
     * @throws ArticleNotFoundException
     */
    public function removeArticleById($id)
    {
        $article = $this->find($id);

        if(null === $article)
        {
            throw new EntityNotFoundException();
        }

        $this->removeArticle($article);
    }

    /**
     * @param Article $article
     *
     * @throws ArticleNotFoundException
     */
    public function removeArticle(Article $article)
    {
        $this->getEntityManager()->remove($article);
    }

    /**
     * @return QueryBuilder
     */
    protected function getBaseQueryBuilder()
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('a')
            ->from('Jme\ArticleBundle\Entity\Article', 'a')
            ->orderBy('a.created', 'DESC');
    }
}
