<?php

namespace Jme\ArticleBundle\Tests\Controller;


use Jme\MainBundle\Component\Test\ServiceTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends ServiceTestCase
{

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

        $this->assertGreaterThan(0, $crawler->filter('html:contains("New article")')->count());
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
    public function GETRequestOnCreateArticleIsDenied()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/fi/artikkeli/luo');

        $this->assertEquals(405, $client->getResponse()->getStatusCode() );
    }
}
