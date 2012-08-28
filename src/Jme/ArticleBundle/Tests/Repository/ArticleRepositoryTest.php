<?php
namespace Jme\ArticleBundle\Tests\Repository;

use \DateTime,
    Doctrine\ORM\EntityManager,
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
        $date1 = new DateTime("2012-02-12 14:00:00");
        $date2 = new DateTime("2012-02-12 12:12:12");
        $date3 = new DateTime("2012-02-11 16:07:28");
        $date4 = new DateTime("2012-02-10 09:12:01");

        $article1 = $this->getFixtureFactory()->get('ArticleBundle\Entity\Article', array('created' => $date2) );
        $article2 = $this->getFixtureFactory()->get('ArticleBundle\Entity\Article', array('created' => $date3) );
        $article3 = $this->getFixtureFactory()->get('ArticleBundle\Entity\Article', array('created' => $date1) );
        $article4 = $this->getFixtureFactory()->get('ArticleBundle\Entity\Article', array('created' => $date4) );

        $this->entityManager->flush();

        $result = $this->repository->fetchLatestArticles(2);

        $this->assertCount(2, $result);
        $this->assertSame($result[0], $article3);
        $this->assertSame($result[1], $article1);
    }
}
