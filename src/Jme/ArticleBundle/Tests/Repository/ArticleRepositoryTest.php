<?php
namespace Jme\ArticleBundle\Tests\Repository;

use Doctrine\ORM\EntityManager,
    Jme\MainBundle\Component\Test\DatabaseTestCase,
    Jme\ArticleBundle\Entity\Article,
    Jme\ArticleBundle\Repository\ArticleRepository,
    Jme\ArticleBundle\Service\ArticleService,
    Jme\ArticleBundle\Service\Exception\ArticleNotSavedException;

class ArticleRepositoryTest extends DatabaseTestCase
{
    /**
     * @var ArticleRepository
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = $this->getContainer()->get('jme_article.repository.article');
    }

    /**
     * @test
     *
     * @group article
     * @group repository
     * @group article-repository
     */
    public function fetchesExpectedArticles()
    {
        $articles = array(
            $this->getFixtureFactory()->get('ArticleBundle\Entity\Article'),
            $this->getFixtureFactory()->get('ArticleBundle\Entity\Article'),
            $this->getFixtureFactory()->get('ArticleBundle\Entity\Article'),
            $this->getFixtureFactory()->get('ArticleBundle\Entity\Article'),
        );

        $this->entityManager->flush();

        $result = $this->repository->fetchLatestArticles(2);

        $this->assertCount(2, $result);
    }
}
