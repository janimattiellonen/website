<?php
namespace Jme\MainBundle\Twig\Extensions;

use \Twig_Environment,
    \Twig_Extension,
    Symfony\Component\HttpFoundation\Session\Session;

class Flash extends Twig_Extension
{
    const TYPE_ERROR = 'error';
    const TYPE_WARNING = 'warning';
    const TYPE_NOTICE = 'notice';
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param Twig_Environment $twig
     * @param Session $session
     */
    public function __construct(Twig_Environment $twig, Session $session)
    {
        $this->twig = $twig;
        $this->session = $session;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'jme_flash' => new \Twig_Function_Method(
                $this, 'flash', array('is_safe' => array('html') )
            ),
        );
    }

    /**
     * @return string
     */
    public function flash()
    {
        $type = $this->getSetFlashType();

        if(null === $type)
        {
            return '';
        }

        //print_r($this->session->getFlashBag()->all());die;

        return $this->twig->render('JmeMainBundle:Twig:message.html.twig', array(
            'type' => $type,
            'messages' => $this->session->getFlashBag()->all(),
        ) );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'flash_extension';
    }

    /**
     * @return string
     */
    protected function getSetFlashType()
    {
        if($this->session->getFlashBag()->has(self::TYPE_ERROR) )
        {
            return self::TYPE_ERROR;
        }
        else if($this->session->getFlashBag()->has(self::TYPE_WARNING) )
        {
            return self::TYPE_WARNING;
        }
        else if($this->session->getFlashBag()->has(self::TYPE_NOTICE) )
        {
            return self::TYPE_NOTICE;
        }

        return null;
    }
}
