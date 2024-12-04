<?php

namespace App\Menu;

use App\Entity\User;
use App\Repository\ContactMessageRepository;
use App\Service\SmartAssetsHelperService;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Bundle\SecurityBundle\Security;

class BackendTopNavMenuBuilder
{
    private FactoryInterface $factory;
    private Security $ss;
    private ContactMessageRepository $cmr;
    private SmartAssetsHelperService $sahs;

    public function __construct(FactoryInterface $factory, Security $ss, ContactMessageRepository $cmr, SmartAssetsHelperService $sahs)
    {
        $this->factory = $factory;
        $this->ss = $ss;
        $this->cmr = $cmr;
        $this->sahs = $sahs;
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
                    'label' => '<i class="fa fa-link"></i>',
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
        if ($user->getTeacher()) {
            if ($user->getTeacher()->getImageName()) {
                $menu
                    ->addChild(
                        'username',
                        [
                            'label' => '<img src="'.$this->sahs->getTeacherImageAssetPath($user->getTeacher(), '60x60').'" class="user-image" alt="'.$user->getFullname().'">'.$user->getFullname(),
                            'uri' => '#',
                        ]
                    )
                    ->setAttribute('class', 'user-menu')
                    ->setExtras(
                        [
                            'safe_label' => true,
                        ]
                    )
                ;
            } else {
                $menu
                    ->addChild(
                        'username',
                        [
                            'label' => '<i class="far fa-user-circle fa-fw mr-3"></i>'.$user->getFullname(),
                            'uri' => '#',
                        ]
                    )
                    ->setExtras(
                        [
                            'safe_label' => true,
                        ]
                    )
                ;
            }
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
