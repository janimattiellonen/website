<?php

namespace Jme\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Form\Form,
    Jme\MainBundle\Component\Controller\BaseController,
    Jme\ArticleBundle\Service\ArticleService,
    Jme\ArticleBundle\Entity\Article,
    Jme\ArticleBundle\Form\Type\ArticleType,
    Jme\ArticleBundle\Service\Exception\ArticleException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Xi\Filelib\FileLibrary;
use Xi\Filelib\Publisher\Publisher;
use Xi\Filelib\Renderer\Renderer;

class DefaultController extends BaseController
{
    public function indexAction()
    {
        return $this->render('JmeArticleBundle:Default:index.html.twig');
    }

    public function filelibAction()
    {
        $appDir = $this->container->get('kernel')->getRootdir();

        /** @var FileLibrary $filelib */
        $filelib = $this->get('xi_filelib');

        $file = $filelib->uploadFile($appDir . '/data/manatus-25.jpg');

        // Renderer renders "raw" files
        /** @var Renderer $renderer */
        $renderer = $this->container->get('xi_filelib.renderer');

        // Publisher publishes and handles published files.
        /** @var Publisher $publisher */
        $publisher = $this->container->get('xi_filelib.publisher');

        if (rand(1, 100) > 50) {
            // Renderer renders.
            return $renderer->render($file, 'lussogrande');
        } else {
            // Publisher publishes and handles published stuff.
            $publisher->publishAllVersions($file);
            return new RedirectResponse($publisher->getUrl($file, 'lussominore'));
        }
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

            $this->get('session')->getFlashBag()->add(
                'notice',
                'The article was successfully removed!'
            );
        }
        catch(ArticleException $e)
        {
            $this->get('session')->getFlashBag()->add(
                'error',
                'Failed to remove article'
            );
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

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    $self->get('translator')->trans('article.created')
                );

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
            $this->get('session')->getFlashBag()->add(
                'error',
                "Failed to open article for editing"
            );

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

                    $this->get('session')->getFlashBag()->add(
                        'notice',
                        $self->get('translator')->trans('article.updated')
                    );

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
            $this->get('session')->getFlashBag()->add(
                'error',
                "Failed to update article"
            );

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
