<?php

namespace Jme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JmeMainBundle:Default:index.html.twig');
    }
}
