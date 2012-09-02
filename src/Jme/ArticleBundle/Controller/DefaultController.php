<?php

namespace Jme\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Form\Form,
    Jme\MainBundle\Component\Controller\BaseController,
    Jme\ArticleBundle\Service\ArticleService,
    Jme\ArticleBundle\Entity\Article,
    Jme\ArticleBundle\Form\Type\ArticleType,
    Jme\ArticleBundle\Service\Exception\ArticleException;

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

    public function removeAction($article)
    {
        try
        {
            $this->getArticleService()->removeArticleById($article);

            $this->get('session')->setFlash('notice', 'The article was successfully removed!');
        }
        catch(ArticleException $e)
        {
            $this->get('session')->setFlash('error', $e->getMessage() );
        }

        return $this->redirectWithRoute('jme_article_latest');
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

                $self->get('session')->setFlash('notice', $self->get('translator')->trans('article.created'));

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

    public function editAction($article)
    {
        try
        {
            $form = $this->createArticleForm($this->getArticleService()->getArticle($article));

            return $this->render('JmeArticleBundle:Default:edit.html.twig', array(
                'form' => $form->createView(),
            ));
        }
        catch(ArticleException $e)
        {
            $this->get('session')->setFlash('error', $e->getMessage() );
            return $this->redirectWithRoute('jme_article_latest');
        }
    }

    public function updateAction($article)
    {
        $self = $this;
        $service = $this->getArticleService();

        try
        {
            $form = $this->createArticleForm($this->getArticleService()->getArticle($article));

            return $this->processForm($form, function() use($form, $service, $self) {
                    $article = $service->saveByForm($form);

                    return $self->createSuccessRedirectResponse(
                        'jme_article_view', array('article' => $article->getId() )
                    );
            },
            function($form) use($self)
            {
                return $self->render('JmeArticleBundle:Default:edit.html.twig', array(
                    'form' => $form->createView(),
                ));
            });
        }
        catch(ArticleException $e)
        {
            $this->get('session')->setFlash('error', $e->getMessage() );
            return $this->redirectWithRoute('jme_article_latest');
        }
    }

    public function latestAction()
    {
        $articles = $this->getArticleService()->listArticles(5);

        return $this->render('JmeArticleBundle:Default:latest.html.twig', array(
            'articles' => $articles,
        ));
    }

    /**
     * @param Article $article
     *
     * @return Form
     */
    protected function createArticleForm(Article $article = null)
    {
        return $this->createForm(new ArticleType(), $article);
    }

    /**
     * @return ArticleService
     */
    protected function getArticleService()
    {
        return $this->get('jme_article.service.article');
    }
}
