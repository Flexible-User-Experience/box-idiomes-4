<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class BackendFilesManagerMenuBuilder.
 *
 * @category Menu
 */
class BackendFilesManagerMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * Methods.
     */

    /**
     * Constructor.
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return ItemInterface
     */
    public function createSideMenu(RequestStack $requestStack)
    {
        $route = $requestStack->getCurrentRequest()->get('_route');
        $menu = $this->factory->createItem('Fitxers');
        $menu
            ->addChild(
                'files',
                [
                    'label' => 'backend.admin.files',
                    'route' => 'admin_app_filedummy_handler',
                    'current' => 'admin_app_filedummy_handler' == $route || 'file_manager' == $route || 'file_manager_rename' == $route || 'file_manager_upload' == $route,
                ]
            )
        ;

        return $menu;
    }
}
