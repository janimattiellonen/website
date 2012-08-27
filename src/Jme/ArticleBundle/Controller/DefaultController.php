<?php

namespace Jme\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Form\Form,
    Jme\MainBundle\Component\Controller\BaseController,
    Jme\ArticleBundle\Entity\Article,
    Jme\ArticleBundle\Form\Type\ArticleType;

class DefaultController extends BaseController
{
    public function indexAction()
    {
        return $this->render('JmeArticleBundle:Default:index.html.twig');
    }

    public function viewAction()
    {
        return $this->render('JmeArticleBundle:Default:view.html.twig', array(
        ));
    }

    public function newAction()
    {
        $form = $this->createArticleForm();

        return $this->render('JmeArticleBundle:Default:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function createAction()
    {
        $self       = $this;
        $service    = $this->getArticleService();
        $form       = $this->createArticleForm();

        return $this->processForm($form, function() use($form, $service, $self){

                $article = $service->saveByForm($form);

                return $self->createSuccessRedirectResponse(
                    'jme_article_view'
                );
            },
            function($form) use($self) {
                return $self->render('JmeArticleBundle:Default:new.html.twig', array(
                    'form' => $form->createView(),
                ));
            }
        );
    }

    /**
     * @return Form
     */
    protected function createArticleForm()
    {
        return $this->createForm(new ArticleType() );
    }

    /**
     * @return Jme\ArticleBundle\Service\ArticleService
     */
    protected function getArticleService()
    {
        return $this->get('jme_article.service.article');
    }
}
