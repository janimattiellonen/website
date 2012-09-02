<?php
namespace Jme\MainBundle\Menu;

use Knp\Menu\FactoryInterface,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\DependencyInjection\ContainerAware,
    Symfony\Bundle\FrameworkBundle\Translation\Translator;

class Builder extends ContainerAware
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @param FactoryInterface $factory
     * @param Translator $translator
     */
    public function __construct(FactoryInterface $factory, Translator $translator)
    {
        $this->factory = $factory;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @return \Knp\Menu\ItemInterface
     */
    public function createMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setCurrentUri($request->getRequestUri() );

        $menu->addChild($this->translator->trans('menu.home'), array('route' => 'jme_article_latest') );
        $menu->addChild($this->translator->trans('menu.article.new'), array('route' => 'jme_article_new') );

        return $menu;
    }
}
