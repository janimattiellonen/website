<?php

namespace Jme\ArticleBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
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
        $client = static::createClient();

        $crawler = $client->request('GET', '/fi/artikkeli/uusi');

        $content = $client->getResponse()->getContent();

        $this->assertGreaterThan(0, $crawler->filter('html:contains("New article")')->count());
        $this->assertContains('<label for="article_title"', $content);
        $this->assertContains('<input type="text" id="article_title"', $content);
    }
}
