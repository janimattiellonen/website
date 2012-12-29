<?php
namespace Jme\ArticleBundle\Twig\Extensions;

use \Twig_Environment,
    \Twig_Extension,
    Jme\ArticleBundle\Entity\Article;

class Tags extends Twig_Extension
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
            'jme_tags' => new \Twig_Function_Method(
                $this, 'tags', array('is_safe' => array('html') )
            ),
        );
    }

    /**
     * @param Article $article
     * @return string
     */
    public function tags(Article $article)
    {
        return $this->twig->render('JmeArticleBundle:Twig:tags.html.twig', array(
            'tags' => $article->getTags(),
        ) );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'article_tagSelector';
    }
}
