<?php

namespace Jme\MainBundle\Component\Test;

use Xi\Doctrine\Fixtures\FieldDef;

class DatabaseTestCase extends ServiceTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->getFixtureFactory()->setEntityNamespace('Jme');

        $this->getFixtureFactory()->persistOnGet(true);

        $this->defineArticle();
    }

    protected function defineArticle()
    {
        $this->getFixtureFactory()->defineEntity('ArticleBundle\Entity\Article', array(
            'title' => FieldDef::sequence('Title_%d'),
            'content' => FieldDef::sequence('Content_%d'),
            'brief' => FieldDef::sequence('Brief_%d'),
        ) );
    }
}
