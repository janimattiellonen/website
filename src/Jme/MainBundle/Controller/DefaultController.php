<?php

namespace Jme\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


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

    public function createNotesAction()
    {
        return new JsonResponse([
            ['id' => 10, 'note' => 'Note1'],
            ['id' => 11, 'note' => 'Note222']
        ]);
        $this->get('logger')->info("ARGS: " . print_r($this->getRequest()->request->all(), true));

        $response = new JsonResponse();
        $response->setData(array(
            'data' => 123
        ));

        return $response;


        return new JsonResponse(['ok' => true], 500);
    }

    public function notesAction()
    {
        return new JsonResponse([
            ['id' => 10, 'note' => 'Note1'],
            ['id' => 11, 'note' => 'Note2']
        ]);
    }

}
