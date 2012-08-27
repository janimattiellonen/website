<?php
namespace Jme\ArticleBundle\Tests\Service;

use Doctrine\ORM\EntityManager,
    Jme\MainBundle\Component\Test\ServiceTestCase,
    Jme\ArticleBundle\Entity\Article,
    Jme\ArticleBundle\Repository\ArticleRepository,
    Jme\ArticleBundle\Service\ArticleService,
    Jme\ArticleBundle\Service\Exception\ArticleNotSavedException;

class ArticleServiceTest extends ServiceTestCase
{
    /**
     * @var EntityManager;
     */
    private $emMock;

    /**
     * @var ArticleRepository
     */
    private $repositoryMock;

    /**
     * @var ArticleService
     */
    private $service;

    public function setUp()
    {
        parent::setUp();

        $this->emMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()->getMock();

        $this->repositoryMock = $this->getMockBuilder('Jme\ArticleBundle\Repository\ArticleRepository')
            ->disableOriginalConstructor()->getMock();

        $this->service = new ArticleService($this->emMock, $this->repositoryMock);
    }

    /**
     * @test
     *
     * @group service
     * @group article
     * @group article-service
     *
     * @expectedException Jme\ArticleBundle\Service\Exception\ArticleNotSavedException
     */
    public function underlyingDatabaseErrorIsCatchedProperly()
    {
        $this->emMock->expects($this->once())
                 ->method('transactional')
                 ->will($this->throwException(new \Exception('Db error') ) );

        $article = $this->createArticle();

        $this->service->save($article);
    }

    /**
     * @test
     *
     * @group service
     * @group article
     * @group article-service
     */
    public function articleIsSaved()
    {
        $article = $this->createArticle();

        $this->emMock->expects($this->once())
            ->method('transactional')
            ->will($this->returnValue($article) );

        $result = $this->service->save($article);

        $this->assertInstanceOf('Jme\ArticleBundle\Entity\Article', $result);
    }

    /**
     * @test
     *
     * @group service
     * @group article
     * @group article-service
     */
    public function listsArticles()
    {
        $amount = 5;

        $this->repositoryMock->expects($this->once() )
            ->method('fetchLatestArticles')
            ->with($amount);

        $this->service->listArticles($amount);
    }

    /**
     * @return \Jme\ArticleBundle\Entity\Article
     */
    protected function createArticle()
    {
        $article = new Article();
        $article->setTitle('Title');
        $article->setContent('Content');

        return $article;
    }
}
