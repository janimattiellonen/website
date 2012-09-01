<?php
namespace Jme\MainBundle\Menu;

use Knp\Menu\FactoryInterface,
    Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    /**
     * @param \Knp\Menu\FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Latest', array('route' => 'jme_article_latest') );
        $menu->addChild('New article', array('route' => 'jme_article_new') );

        return $menu;
    }
}
