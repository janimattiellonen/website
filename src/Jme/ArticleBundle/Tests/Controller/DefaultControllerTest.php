<?php

namespace Jme\ArticleBundle\Tests\Controller;


use Jme\MainBundle\Component\Test\DatabaseTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends DatabaseTestCase
{
    /**
     * @test
     *
     * @group article
     * @group controller
     * @group article-controller
     */
    public function showsLatestArticles()
    {
        $articles = array(
            $this->getFixtureFactory()->get('ArticleBundle\Entity\Article'),
            $this->getFixtureFactory()->get('ArticleBundle\Entity\Article'),
            $this->getFixtureFactory()->get('ArticleBundle\Entity\Article'),
            $this->getFixtureFactory()->get('ArticleBundle\Entity\Article'),
            $this->getFixtureFactory()->get('ArticleBundle\Entity\Article'),
            $this->getFixtureFactory()->get('ArticleBundle\Entity\Article'),
            $this->getFixtureFactory()->get('ArticleBundle\Entity\Article'),
            $this->getFixtureFactory()->get('ArticleBundle\Entity\Article'),
        );

        $this->entityManager->flush();

        $client = $this->createClient();

        $crawler = $client->request('GET', '/fi');

        $this->assertCount(5, $crawler->filter('div.article') );
    }

    /**
     * @test
     *
     * @group article
     * @group controller
     * @group article-controller
     */
    public function showsArticle()
    {
        $article = $this->getFixtureFactory()->get('ArticleBundle\Entity\Article');
        $this->entityManager->flush();

        $client = $this->createClient();

        $crawler = $client->request('GET', '/fi/artikkeli/' . $article->getId() );


        $this->assertCount(1, $crawler->filter('div.article') );
    }

    /**
     * @test
     *
     * @group article
     * @group controller
     * @group article-controller
     */
    public function rendersNewArticleForm()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/fi/artikkeli/uusi');

        $content = $client->getResponse()->getContent();

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Uusi artikkeli")')->count());
        $this->assertContains('<label for="article_title"', $content);
        $this->assertContains('<input type="text" id="article_title"', $content);
    }

    /**
     * @test
     *
     * @group article
     * @group controller
     * @group article-controller
     */
    public function articleIsSaved()
    {
        $client = $this->createClient();

        $repository = $this->container->get('jme_article.repository.article');

        $crawler = $client->request('POST', '/fi/artikkeli/luo', array(
            'article' => array(
                'title' => 'Title1',
                'content' => 'Content1',
                'brief' => 'brief',
            )
        ) );

        $articles = $repository->findAll();

        $this->assertCount(1, $articles);

        $article = $articles[0];

        $this->assertInstanceOf('Jme\ArticleBundle\Entity\Article', $article);
        $this->assertEquals('Title1', $article->getTitle() );
        $this->assertEquals('Content1', $article->getContent() );
    }

    /**
     * @test
     *
     * @group article
     * @group controller
     * @group article-controller
     */
    public function articleIsNotSavedWithInvalidFormSubmission()
    {
        $client = $this->createClient();

        $crawler = $client->request('POST', '/fi/artikkeli/luo', array(
            'article' => array(
                'title' => 'e',
                'content' => 'e',
                'brief' => 'e',
            )
        ) );

        $this->assertGreaterThan(0, $crawler->filter('html:contains("The title must have at least")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("The brief must have at least")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("The content must have at least")')->count());
    }

    /**
     * @test
     *
     * @group article
     * @group controller
     * @group article-controller
     */
    public function articleIsNotUpdatedWithInvalidFormSubmission()
    {
        $article = $this->getFixtureFactory()->get('ArticleBundle\Entity\Article');
        $this->entityManager->flush();

        $client = $this->createClient();

        $crawler = $client->request('POST', '/fi/artikkeli/paivita/' . $article->getId(), array(
            'article' => array(
                'title' => 'e',
                'content' => 'e',
                'brief' => 'e',
            )
        ) );

        $this->assertGreaterThan(0, $crawler->filter('html:contains("The title must have at least")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("The brief must have at least")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("The content must have at least")')->count());
    }

    /**
     * @test
     *
     * @group article
     * @group controller
     * @group article-controller
     */
    public function articleIsRemoved()
    {
        $client = $this->createClient();

        $client->followRedirects();

        $article = $this->getFixtureFactory()->get('ArticleBundle\Entity\Article');
        $this->entityManager->flush();

        $id = $article->getId();

        $repository = $this->container->get('jme_article.repository.article');

        $crawler = $client->request('get', '/fi/artikkeli/poista/' . $article->getId() );

        $this->assertGreaterThan(0, $crawler->filter('html:contains("The article was successfully removed!")')->count());

        $obj = $repository->find($id);
        $this->assertNull($obj);
    }

    /**
     * @test
     *
     * @group article
     * @group controller
     * @group article-controller
     */
    public function removalOfNonExistingArticleResultsinExpectedError()
    {
        $client = $this->createClient();

        $client->followRedirects();

        $crawler = $client->request('get', '/fi/artikkeli/poista/666');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Article was not found")')->count());
    }

    /**
     * @test
     *
     * @group article
     * @group controller
     * @group article-controller
     */
    public function GETRequestOnCreateArticleIsDenied()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/fi/artikkeli/luo');

        $this->assertEquals(405, $client->getResponse()->getStatusCode() );
    }

    /**
     * @test
     *
     * @group article
     * @group controller
     * @group article-controller
     */
    public function articleIsOpenedForEditing()
    {
        $article = $this->getFixtureFactory()->get('ArticleBundle\Entity\Article');

        $this->entityManager->flush();

        $client = $this->createClient();

        $crawler = $client->request('GET', '/fi/artikkeli/muokkaa/' . $article->getId() );

        $this->assertGreaterThan(0, $crawler->filter('body:contains("Muokkaa artikkelia")')->count());
        $this->assertGreaterThan(0, $crawler->filter('input[name="article[title]"]')->count());
        $this->assertGreaterThan(0, $crawler->filter('input[value="Title_1"]')->count() );
    }

    /**
     * @test
     *
     * @group article
     * @group controller
     * @group article-controller
     */
    public function handlesOpeningNonExistingArticleForEditing()
    {
        $client = $this->createClient();

        $client->followRedirects();

        $crawler = $client->request('get', '/fi/artikkeli/muokkaa/666');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Article was not found")')->count());
    }


}
