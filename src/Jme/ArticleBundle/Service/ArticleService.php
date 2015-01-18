<?php
namespace Jme\ArticleBundle\Service;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityManager;

use \Exception;

use FPN\TagBundle\Entity\TagManager;

use Jme\ArticleBundle\Service\Exception\ArticleNotSavedException;
use Jme\ArticleBundle\Service\Exception\ArticleNotRemovedException;
use Jme\ArticleBundle\Service\Exception\ArticleNotFoundException;
use Jme\ArticleBundle\Repository\ArticleRepository;
use Jme\ArticleBundle\Entity\Article;

use Jme\Component\Service\AbstractBaseService;
use Jme\UserBundle\Service\UserService;
use Symfony\Component\Form\Form;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ArticleService extends AbstractBaseService
{
    /**
     * @var ArticleRepository
     */
    protected $articleRepository;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var TagManager
     */
    protected $tagManager;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @param EntityManager     $em
     * @param ArticleRepository $articleRepository
     * @param TagManager        $tagManager
     * @param UserService       $userService
     */
    public function __construct(
        EntityManager $em,
        ArticleRepository $articleRepository,
        TagManager $tagManager,
        UserService $userService)
    {
        parent::__construct($em);

        $this->articleRepository    = $articleRepository;
        $this->tagManager           = $tagManager;
        $this->userService          = $userService;
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
                $article->setSlug(null);
                $em->persist($article);
                $em->flush();

                $self->tagManager->saveTagging($article);

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
        if (is_numeric($id)) {
            $article = $this->articleRepository->find($id);
        } else {
            $article = $this->articleRepository->findOneBy(array('slug' => $id));
        }

        if (null === $article) {
            throw new ArticleNotFoundException();
        }

        $this->tagManager->loadTagging($article);

        return $article;
    }

    /**
     * @param int       $amount     *
     * @return array
     */
    public function listArticles($amount)
    {
        $articles = $this->articleRepository->fetchLatestArticles($amount, $this->userService->isLoggedIn());

        foreach ($articles as &$article) {
            $this->tagManager->loadTagging($article);
        }

        return $articles;
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
