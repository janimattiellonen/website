<?php
namespace Jme\MainBundle\Tests\Entity;

use Jme\MainBundle\Component\Test\DatabaseTestCase,
    Jme\ArticleBundle\Entity\Article,
    \DateTime;


class ArticleTest extends DatabaseTestCase
{
    /**
     * @var \Symfony\Component\Validator\Validator
     */
    protected $validator;

    public function setUp()
    {
        parent::setUp();

        $this->validator = $this->getContainer()->get('validator');
    }

    /**
     * @test
     *
     * @group entity
     * @group article
     * @group article-entity
     */
    public function titleIsTooShort()
    {
        $article = $this->createArticle();
        $article->setTitle("fo");
        $errors = $this->validator->validate($article);
        $this->assertCount(1, $errors);
    }

    /**
     * @test
     *
     * @group entity
     * @group article
     * @group article-entity
     */
    public function titleIsTooLong()
    {

        $article = $this->createArticle();
        $article->setTitle(str_repeat("i", 129));
        $errors = $this->validator->validate($article);
        $this->assertCount(1, $errors);
    }

    /**
     * @test
     *
     * @group entity
     * @group article
     * @group article-entity
     */
    public function contentIsTooShort()
    {
        $article = $this->createArticle();
        $article->setContent("fo");
        $errors = $this->validator->validate($article);
        $this->assertCount(1, $errors);
    }

    /**
     * @test
     *
     * @group entity
     * @group article
     * @group article-entity
     */
    public function articleEntityIsValid()
    {
        $article = $this->createArticle();
        $errors = $this->validator->validate($article);
        $this->assertCount(0, $errors);
    }

    /**
     * @test
     *
     * @group entity
     * @group article
     * @group article-entity
     */
    public function createdTimestampIsSetButNotUpdatedTimestamp()
    {
        $now = new DateTime();
        $article = $this->getFixtureFactory()->get('ArticleBundle\Entity\Article');
        $this->entityManager->flush();

        $created = $article->getCreated();

        $this->assertInstanceOf('\DateTime', $created);
        $this->assertEquals($now->format('d.m.Y') , $created->format('d.m.Y') );
    }

    /**
     * @test
     *
     * @group entity
     * @group article
     * @group article-entity
     */
    public function updatedTimestampIsSet()
    {
        $now = new DateTime();

        $article = $this->getFixtureFactory()->get('ArticleBundle\Entity\Article');
        $this->entityManager->flush();
        $created = $article->getCreated();

        usleep(1000000);
        $article->setContent("Foo");
        $this->entityManager->flush();
        $updated = $article->getUpdated();

        $this->assertInstanceOf('\DateTime', $updated);
        $this->assertEquals($now->format('d.m.Y') , $created->format('d.m.Y') );
        $this->assertGreaterThan($created->getTimestamp(), $updated->getTimestamp() );
    }

    /**
     * @return Article
     */
    protected function createArticle()
    {
        $article = new Article();
        $article->setTitle("title");
        $article->setContent("content");

        return $article;
    }
}
