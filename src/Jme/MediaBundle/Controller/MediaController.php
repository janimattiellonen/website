<?php

namespace Jme\MediaBundle\Controller;

use Jme\MediaBundle\Form\Type\MediaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Jme\MainBundle\Component\Controller\BaseController;

class MediaController extends BaseController
{
    public function selectFileAction()
    {
        $form = $this->get('form.factory')->createBuilder(new MediaType())->getForm();

        return $this->render('JmeMediaBundle:Media:select-file.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function uploadFileAction(Request $request)
    {
        $form = $this->get('form.factory')->createBuilder(new MediaType())->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getMediaService()->saveByForm($form);
        } else {
            return $this->render('JmeMediaBundle:Media:select-file.html.twig', array(
                'form' => $form->createView(),
            ));
        }
    }

    public function listFilesAction()
    {
        return $this->render('JmeMediaBundle:Media:list-files.html.twig');
    }

    /**
     * @return \Jme\MediaBundle\Service\MediaService
     */
    protected function getMediaService()
    {
        return $this->get('jme_media.service.media');
    }
}
 