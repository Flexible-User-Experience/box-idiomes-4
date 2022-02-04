<?php

namespace App\Menu;

use App\Enum\UserRolesEnum;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class FrontendMenuBuilder
{
    private FactoryInterface $factory;
    private Security $ss;
    private RequestStack $rs;
    private string $ppo;

    public function __construct(FactoryInterface $factory, Security $ss, RequestStack $rs, string $ppo)
    {
        $this->factory = $factory;
        $this->ss = $ss;
        $this->rs = $rs;
        $this->ppo = $ppo;
    }

    public function createTopMenu(): ItemInterface
    {
        $current = '';
        if ($this->rs->getCurrentRequest()) {
            $current = $this->rs->getCurrentRequest()->get('_route');
        }
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav ms-auto mb-2 mb-lg-0');
        if ($this->ss->isGranted(UserRolesEnum::ROLE_CMS)) {
            $menu->addChild(
                'admin',
                [
                    'label' => 'frontend.menu.cms',
                    'route' => 'sonata_admin_dashboard',
                    'attributes' => [
                        'class' => 'nav-item',
                    ],
                    'linkAttributes' => [
                        'class' => 'nav-link',
                    ],
                ]
            );
        }
        $menu->addChild(
            'app_services',
            [
                'label' => 'frontend.menu.services',
                'route' => 'app_services',
                'attributes' => [
                    'class' => 'nav-item',
                ],
                'linkAttributes' => [
                    'class' => 'nav-link'.('app_services' === $current ? ' active' : ''),
                ],
            ]
        );
        $menu->addChild(
            'app_academy',
            [
                'label' => 'frontend.menu.academy',
                'route' => 'app_academy',
                'attributes' => [
                    'class' => 'nav-item',
                ],
                'linkAttributes' => [
                    'class' => 'nav-link'.('app_academy' === $current ? ' active' : ''),
                ],
            ]
        );
        $menu->addChild(
            'app_contact',
            [
                'label' => 'frontend.menu.contact',
                'route' => 'app_contact',
                'attributes' => [
                    'class' => 'nav-item',
                ],
                'linkAttributes' => [
                    'class' => 'nav-link'.('app_contact' === $current ? ' active' : ''),
                ],
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
                        'class' => 'c-white'.('app_pre_register' === $current ? ' active' : ''),
                    ],
                ]
            );
        }

        return $menu;
    }
}
