<?php
namespace Jme\ArticleBundle\Service;

use Jme\ArticleBundle\Service\Exception\ArticleNotSavedException,
    Jme\ArticleBundle\Service\Exception\ArticleNotRemovedException,
    Jme\ArticleBundle\Service\Exception\ArticleNotFoundException,
    Jme\ArticleBundle\Repository\ArticleRepository,
    Jme\ArticleBundle\Entity\Article,
    Symfony\Component\Form\Form,
    Doctrine\ORM\EntityNotFoundException,
    Doctrine\ORM\EntityManager,
    \Exception;

class ArticleService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ArticleRepository
     */
    protected $articleRepository;

    /**
     * @param EntityManager $em
     * @param ArticleRepository $articleRepository
     */
    public function __construct(EntityManager $em, ArticleRepository $articleRepository)
    {
        $this->em                   = $em;
        $this->articleRepository    = $articleRepository;
    }

    /**
     * @param Form $form
     *
     * @return Article
     *
     * @throws ArticleNotFoundException
     */
    public function saveByForm(Form $form)
    {
        return $this->save($form->getData() );
    }

    /**
     * @param Article $article
     *
     * @return Article
     *
     * @throws ArticleNotFoundException
     */
    public function save(Article $article)
    {
        try
        {
            return $this->em->transactional(function(EntityManager $em) use($article) {
                $em->persist($article);

                return $article;
            });
        }
        catch(Exception $e)
        {
            throw new ArticleNotSavedException($e->getPrevious() );
        }
    }

    /**
     * @param int $id
     *
     * @return Article
     */
    public function getArticle($id)
    {
        return $this->articleRepository->find($id);
    }

    /**
     * @param int $amount
     * @return array
     */
    public function listArticles($amount)
    {
        return $this->articleRepository->fetchLatestArticles($amount);
    }

    /**
     * @param int $articleId
     */
    public function removeArticleById($articleId)
    {

        try
        {
            $this->articleRepository->removeArticleById($articleId);
            $this->em->flush();
        }
        catch(EntityNotFoundException $e)
        {
            throw new ArticleNotFoundException($e->getPrevious() );
        }
        catch(Exception $e)
        {
            throw new ArticleNotRemovedException($e->getPrevious() );
        }
    }
}
