<?php
namespace Jme\ArticleBundle\Twig\Extensions;

use \Twig_Environment,
    \Twig_Extension;

class TagSelector extends Twig_Extension
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
            'jme_article_tag_selector' => new \Twig_Function_Method(
                $this, 'tagSelector', array('is_safe' => array('html') )
            ),
        );
    }

    public function tagSelector()
    {
        return $this->twig->render('JmeArticleBundle:Twig:tagSelector.html.twig', array(
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
