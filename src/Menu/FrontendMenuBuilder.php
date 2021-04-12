<?php

namespace App\Menu;

use App\Enum\UserRolesEnum;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Security;

class FrontendMenuBuilder
{
    private FactoryInterface $factory;
    private Security $ss;
    private string $ppo;

    public function __construct(FactoryInterface $factory, Security $ss, string $ppo)
    {
        $this->factory = $factory;
        $this->ss = $ss;
        $this->ppo = $ppo;
    }

    public function createTopMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        if ($this->ss->isGranted(UserRolesEnum::ROLE_CMS)) {
            $menu->addChild(
                'admin',
                [
                    'label' => 'frontend.menu.cms',
                    'route' => 'sonata_admin_dashboard',
                ]
            );
        }
        $menu->addChild(
            'app_services',
            [
                'label' => 'frontend.menu.services',
                'route' => 'app_services',
            ]
        );
        $menu->addChild(
            'app_academy',
            [
                'label' => 'frontend.menu.academy',
                'route' => 'app_academy',
            ]
        );
        $menu->addChild(
            'app_contact',
            [
                'label' => 'frontend.menu.contact',
                'route' => 'app_contact',
            ]
        );
        // activate Preregister top menu option conditionally
        if ($this->ppo) {
            $menu->addChild(
                'app_pre_register',
                [
                    'label' => 'frontend.menu.preregisters',
                    'route' => 'app_pre_register',
                    'attributes' => [
                        'class' => 'violet-background',
                    ],
                    'linkAttributes' => [
                        'class' => 'c-white',
                    ],
                ]
            );
        }

        return $menu;
    }
}
