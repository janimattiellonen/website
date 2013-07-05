<?php

namespace Jme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JmeMainBundle:Default:index.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('JmeMainBundle:Default:about.html.twig');
    }

    public function projectsAction()
    {
        return $this->render('JmeMainBundle:Default:projects.html.twig');
    }
}
