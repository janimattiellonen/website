<?php
namespace Jme\ArticleBundle\Service;

use Jme\ArticleBundle\Service\Exception\ArticleNotSavedException,
    Jme\ArticleBundle\Entity\Article,
    Symfony\Component\Form\Form,
    Doctrine\ORM\EntityManager;

class ArticleService
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
        catch(\Exception $e)
        {
            throw new ArticleNotSavedException($e->getMessage() );
        }
    }
}
