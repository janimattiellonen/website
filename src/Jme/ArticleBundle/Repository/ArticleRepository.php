<?php
namespace Jme\ArticleBundle\Repository;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Doctrine\ORM\EntityNotFoundException,
    Jme\ArticleBundle\Entity\Article,
    Jme\ArticleBundle\Service\Exception\ArticleNotFoundException;

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
