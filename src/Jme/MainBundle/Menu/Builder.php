<?php
namespace Jme\MainBundle\Menu;

use Knp\Menu\FactoryInterface,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\DependencyInjection\ContainerAware,
    Symfony\Component\Security\Core\SecurityContext,
    Symfony\Bundle\FrameworkBundle\Translation\Translator;

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
     * @param FactoryInterface $factory
     * @param SecurityContext $secutiryContext
     * @param Translator $translator
     */
    public function __construct(FactoryInterface $factory, SecurityContext $securityContext, Translator $translator)
    {
        $this->factory          = $factory;
        $this->securityContext  = $securityContext;
        $this->translator       = $translator;
    }

    /**
     * @param Request $request
     * @return \Knp\Menu\ItemInterface
     */
    public function createMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');


        $menu->addChild($this->translator->trans('menu.home'), array('route' => 'jme_article_latest') );
        $menu->addChild($this->translator->trans('menu.about'), array('route' => 'JmeMainBundle_about') );
        $menu->addChild($this->translator->trans('menu.projects'), array('route' => 'JmeMainBundle_projects') );

        if($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') )
        {
            $menu->addChild($this->translator->trans('menu.article.new'), array('route' => 'jme_article_new') );
            $menu->addChild($this->translator->trans('menu.article.logout'), array('route' => 'fos_user_security_logout') );
        }

        return $menu;
    }
}
