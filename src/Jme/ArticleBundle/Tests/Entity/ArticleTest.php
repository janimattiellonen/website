<?php
namespace Jme\MainBundle\Tests\Entity;

use Jme\MainBundle\Component\Test\ServiceTestCase,
    Jme\ArticleBundle\Entity\Article,
    Symfony\Component\Validator\ValidatorFactory;


class ArticleTest extends ServiceTestCase
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
