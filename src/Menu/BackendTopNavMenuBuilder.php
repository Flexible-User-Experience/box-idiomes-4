<?php

namespace App\Menu;

use App\Entity\User;
use App\Enum\UserRolesEnum;
use App\Repository\ContactMessageRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Security;

class BackendTopNavMenuBuilder
{
    private FactoryInterface $factory;
    private Security $ss;
    private ContactMessageRepository $cmr;

    public function __construct(FactoryInterface $factory, Security $ss, ContactMessageRepository $cmr)
    {
        $this->factory = $factory;
        $this->ss = $ss;
        $this->cmr = $cmr;
    }

    public function createTopNavMenu(): ItemInterface
    {
        /** @var User $user */
        $user = $this->ss->getUser();
        $menu = $this->factory->createItem('topnav');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu
            ->addChild(
                'homepage',
                [
                    'label' => '<i class="fa fa-globe"></i>',
                    'route' => 'app_homepage',
                ]
            )
            ->setExtras(
                [
                    'safe_label' => true,
                ]
            )
        ;
        if ($this->cmr->getNotReadMessagesAmount() > 0) {
            $menu
                ->addChild(
                    'messages',
                    [
                        'label' => '<i class="fa fa-envelope text-danger"></i> <span class="text-danger">'.$this->cmr->getNotReadMessagesAmount().'</span>',
                        'route' => 'admin_app_contactmessage_list',
                    ]
                )
                ->setExtras(
                    [
                        'safe_label' => true,
                    ]
                )
            ;
        }
        if ($this->ss->isGranted(UserRolesEnum::ROLE_ADMIN)) {
            $menu
                ->addChild(
                    'username',
                    [
                        'label' => $user->getFullname(),
                        'route' => 'admin_app_user_edit',
                        'routeParameters' => [
                            'id' => $user->getId(),
                        ],
                    ]
                )
            ;
        } else {
            $menu
                ->addChild(
                    'username',
                    [
                        'label' => $user->getFullname(),
                        'uri' => '#',
                    ]
                )
            ;
        }
        $menu
            ->addChild(
                'logout',
                [
                    'label' => '<i class="fa fa-power-off text-success"></i>',
                    'route' => 'admin_app_logout',
                ]
            )
            ->setExtras(
                [
                    'safe_label' => true,
                ]
            )
        ;

        return $menu;
    }
}
