<?php

namespace Jme\ArticleBundle\Controller;

use Jme\ArticleBundle\Entity\Article;
use Jme\ArticleBundle\Form\Type\ArticleType;
use Jme\ArticleBundle\Service\ArticleService;
use Jme\ArticleBundle\Service\Exception\ArticleException;
use Jme\ArticleBundle\Service\Exception\ArticleNotFoundException;
use Jme\MainBundle\Component\Controller\BaseController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ciconia\Ciconia;
use Ciconia\Extension\Gfm;

class DefaultController extends BaseController
{
    public function indexAction()
    {
        return $this->render('JmeArticleBundle:Default:index.html.twig');
    }

    public function viewAction($article)
    {
		try {
			$article = $this->getArticleService()->getArticle($article);

			if (!$this->userIsLoggedIn() && !$article->isPublished()) {
				throw new NotFoundHttpException("Page not found");
			}

			return $this->render('JmeArticleBundle:Default:view.html.twig', array(
				'article' => $article,
			));
		}
		catch(ArticleNotFoundException $e) {
			throw new NotFoundHttpException("Page not found");
		}
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
        $ciconia = new Ciconia();
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
