<?php
namespace Jme\ArticleBundle\Twig\Extensions;

use Ciconia\Ciconia;

class Markdown extends \Twig_Extension
{
    /**
     * @var Ciconia
     */
    private $ciconia;

    /**
     * @param Ciconia $ciconia
     */
    public function __construct(Ciconia $ciconia)
    {
        $this->ciconia = $ciconia;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('markdown', array($this, 'markdown'), array('is_safe' => array('html')))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'markdown';
    }

    /**
     * Renders HTML
     */
    public function markdown($string)
    {
        return $this->ciconia->render($string);
    }
}
