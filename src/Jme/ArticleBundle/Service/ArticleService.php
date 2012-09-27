<?php
namespace Jme\ArticleBundle\Service;

use Jme\ArticleBundle\Service\Exception\ArticleNotSavedException,
    Jme\ArticleBundle\Service\Exception\ArticleNotRemovedException,
    Jme\ArticleBundle\Service\Exception\ArticleNotFoundException,
    Jme\ArticleBundle\Repository\ArticleRepository,
    Jme\ArticleBundle\Entity\Article,
    Symfony\Component\Form\Form,
    Symfony\Component\DependencyInjection\ContainerInterface,
    Doctrine\ORM\EntityNotFoundException,
    Doctrine\ORM\EntityManager,
    Xi\Bundle\TagBundle\Service\AbstractTaggableService,
    \Exception;

class ArticleService extends AbstractTaggableService
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
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param EntityManager $em
     * @param ArticleRepository $articleRepository
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, ArticleRepository $articleRepository, ContainerInterface $container)
    {
        $this->em                   = $em;
        $this->articleRepository    = $articleRepository;
        $this->container            = $container;

        parent::__construct($container);
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
        $self = $this;
        try
        {
            return $this->em->transactional(function(EntityManager $em) use($article, $self) {
                $em->persist($article);
                $em->flush();

                $self->getTagService()->getTagManager()->saveTagging($article);

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
     *
     * @throws ArticleNotFoundException
     */
    public function getArticle($id)
    {
        $article = $this->articleRepository->find($id);

        if(null === $article)
        {
            throw new ArticleNotFoundException();
        }

        $this->getTagService()->getTagManager()->loadTagging($article);

        return $article;
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

    /**
     * get taggable resource name
     *
     * @return string
     */
    public function getTaggableType()
    {
        return 'article';
    }

    /**
     * @param array $ids
     * @param array $options
     * @param array $tagNames
     * @return resources
     */
    public function getTaggedResourcesByIds(array $ids, array $options, array $tagNames)
    {
        return $this->articleRepository->findById($ids);
    }
}
