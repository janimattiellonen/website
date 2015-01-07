<?php
namespace Jme\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class Builder extends ContainerAware
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var SecurityContext
     */
    protected $securityContext;

    /**
     * @var Translator
     */
    protected $translator;

	/**
	 * @var Router
	 */
	protected $router;

    /**
     * @param FactoryInterface $factory
     * @param SecurityContext $secutiryContext
     * @param Translator $translator
	 * @param Router $router
     */
    public function __construct(
		FactoryInterface $factory, 
		SecurityContext $securityContext, 
		Translator $translator, 
		Router $router)
    {
        $this->factory          = $factory;
        $this->securityContext  = $securityContext;
        $this->translator       = $translator;
		$this->router			= $router;
    }

    /**
     * @param Request $request
     * @return \Knp\Menu\ItemInterface
     */
    public function createMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild($this->translator->trans('menu.home'), ['uri' => $this->router->generate('jme_article_latest')] );
        $menu->addChild($this->translator->trans('menu.about'), ['uri' => $this->router->generate('jme_article_latest') . '#a-about'] );
        $menu->addChild($this->translator->trans('menu.projects'), ['uri' => $this->router->generate('jme_article_latest') . '#a-projects'] );

        if($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') )
        {
            $menu->addChild($this->translator->trans('menu.article.new'), ['route' => 'jme_article_new'] );
            $menu->addChild($this->translator->trans('menu.media.media-bank'), ['route' => 'jme_media_list_files'] );
            $menu->addChild($this->translator->trans('menu.media.upload'), ['route' => 'jme_media_select_file'] );

            $menu->addChild($this->translator->trans('menu.article.logout'), ['route' => 'fos_user_security_logout'] );
        }

        return $menu;
    }
}
