<?php

namespace Jme\ArticleBundle\Twig\Extensions;

use Jme\ArticleBundle\Entity\Article,
    \Twig_Environment,
    \Twig_Extension;

class ArticleControls extends Twig_Extension
{
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'jme_article_controls' => new \Twig_Function_Method(
                $this, 'controls', array('is_safe' => array('html') )
            ),
        );
    }

    /**
     * @param Article $article
     *
     * @return string
     */
    public function controls(Article $article)
    {
        return $this->twig->render('JmeArticleBundle:Twig:controls.html.twig', array(
            'article' => $article,
        ) );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'article_controls';
    }
}