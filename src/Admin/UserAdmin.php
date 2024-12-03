<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\Teacher;
use App\Entity\User;
use App\Enum\UserRolesEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class UserAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'User';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::ASC;
        $sortValues[DatagridInterface::SORT_BY] = 'username';
    }

    public function generateBaseRoutePattern(bool $isChildAdmin = false): string
    {
        return 'users';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('batch');
        $collection->remove('export');
        $collection->remove('show');
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray('backend.admin.general', 3))
            ->add(
                'firstname',
                null,
                [
                    'label' => 'backend.admin.user.firstname',
                    'required' => false,
                ]
            )
            ->add(
                'lastname',
                null,
                [
                    'label' => 'backend.admin.user.lastname',
                    'required' => false,
                ]
            )
            ->add(
                'username',
                null,
                [
                    'label' => 'backend.admin.user.username',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.user.email',
                ]
            )
            ->add(
                'plainPassword',
                TextType::class,
                [
                    'label' => 'backend.admin.user.plain_password',
                    'required' => (!$this->getSubject() || is_null($this->getSubject()->getId())),
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls', 3))
            ->add(
                'teacher',
                EntityType::class,
                [
                    'label' => 'backend.admin.user.teacher',
                    'required' => false,
                    'class' => Teacher::class,
                    'query_builder' => $this->em->getRepository(Teacher::class)->getEnabledSortedByNameQB(),
                ]
            )
            ->add(
                'enabled',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.enabled',
                    'required' => false,
                ]
            )
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.user.roles',
                    'choices' => UserRolesEnum::getEnumArray(),
                    'multiple' => true,
                    'expanded' => true,
                ]
            )
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'teacher',
                null,
                [
                    'label' => 'backend.admin.user.teacher',
                    'field_type' => EntityType::class,
                    'field_options' => [
                        'expanded' => false,
                        'multiple' => false,
                        'class' => Teacher::class,
                        'query_builder' => $this->em->getRepository(Teacher::class)->getEnabledSortedByNameQB(),
                    ],
                ]
            )
            ->add(
                'username',
                null,
                [
                    'label' => 'backend.admin.user.username',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.user.email',
                ]
            )
            ->add(
                'enabled',
                null,
                [
                    'label' => 'backend.admin.enabled',
                ]
            )
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add(
                'teacher',
                null,
                [
                    'label' => 'backend.admin.user.teacher',
                    'editable' => false,
                ]
            )
            ->add(
                'username',
                null,
                [
                    'label' => 'backend.admin.user.username',
                    'editable' => true,
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.user.email',
                    'editable' => true,
                ]
            )
            ->add(
                'roles',
                null,
                [
                    'label' => 'backend.admin.user.roles',
                    'template' => 'Admin/Cells/list__cell_user_roles.html.twig',
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'enabled',
                null,
                [
                    'label' => 'backend.admin.enabled',
                    'editable' => true,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                ListMapper::NAME_ACTIONS,
                null,
                [
                    'label' => 'backend.admin.actions',
                    'header_style' => 'width:83px',
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'edit' => [
                            'template' => 'Admin/Buttons/list__action_edit_button.html.twig',
                        ],
                        'delete' => [
                            'template' => 'Admin/Buttons/list__action_delete_button.html.twig',
                        ],
                    ],
                ]
            )
        ;
    }

    public function configureExportFields(): array
    {
        return [
            'username',
            'email',
            'enabled',
        ];
    }

    /**
     * @param User $object
     */
    public function prePersist($object): void
    {
        $object
            ->setUsernameCanonical($object->getUsername())
            ->setEmailCanonical($object->getEmail())
        ;
    }
}
