<?php

namespace Jme\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Form\Form,
    Jme\MainBundle\Component\Controller\BaseController,
    Jme\ArticleBundle\Service\ArticleService,
    Jme\ArticleBundle\Entity\Article,
    Jme\ArticleBundle\Form\Type\ArticleType;

class DefaultController extends BaseController
{
    public function indexAction()
    {
        return $this->render('JmeArticleBundle:Default:index.html.twig');
    }

    public function viewAction($article)
    {
        return $this->render('JmeArticleBundle:Default:view.html.twig', array(
            'article' => $this->getArticleService()->getArticle($article),
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
                    'jme_article_view', array('article' => $article->getId() )
                );
            },
            function($form) use($self) {
                return $self->render('JmeArticleBundle:Default:new.html.twig', array(
                    'form' => $form->createView(),
                ));
            }
        );
    }

    public function latestAction()
    {
        $articles = $this->getArticleService()->listArticles(5);

        return $this->render('JmeArticleBundle:Default:latest.html.twig', array(
            'articles' => $articles,
        ));
    }

    /**
     * @return Form
     */
    protected function createArticleForm()
    {
        return $this->createForm(new ArticleType() );
    }

    /**
     * @return ArticleService
     */
    protected function getArticleService()
    {
        return $this->get('jme_article.service.article');
    }
}
