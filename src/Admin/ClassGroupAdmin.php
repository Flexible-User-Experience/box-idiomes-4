<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;

final class ClassGroupAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'ClassGroup';
    protected $baseRoutePattern = 'classrooms/group';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::ASC;
        $sortValues[DatagridInterface::SORT_BY] = 'code';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection
            ->add('emails', $this->getRouterIdParameter().'/get-group-emails')
            ->remove('delete')
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray('backend.admin.general', 3))
            ->add(
                'code',
                null,
                [
                    'label' => 'backend.admin.class_group.code',
                    'required' => true,
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.class_group.name',
                    'required' => false,
                ]
            )
            ->add(
                'book',
                null,
                [
                    'label' => 'backend.admin.class_group.book',
                    'required' => false,
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls', 3))
            ->add(
                'color',
                ColorType::class,
                [
                    'label' => 'backend.admin.teacher.color',
                    'required' => true,
                ]
            )
            ->add(
                'isForPrivateLessons',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.is_for_private_lessons',
                    'required' => false,
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
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'code',
                null,
                [
                    'label' => 'backend.admin.class_group.code',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.class_group.name',
                ]
            )
            ->add(
                'book',
                null,
                [
                    'label' => 'backend.admin.class_group.book',
                ]
            )
            ->add(
                'isForPrivateLessons',
                null,
                [
                    'label' => 'backend.admin.is_for_private_lessons',
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
                'code',
                null,
                [
                    'label' => 'backend.admin.class_group.code',
                    'editable' => true,
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.class_group.name',
                    'editable' => true,
                ]
            )
            ->add(
                'book',
                null,
                [
                    'label' => 'backend.admin.class_group.book',
                    'editable' => true,
                ]
            )
            ->add(
                'color',
                null,
                [
                    'label' => 'backend.admin.class_group.color',
                    'template' => 'Admin/Cells/list__cell_class_group_color.html.twig',
                ]
            )
            ->add(
                'isForPrivateLessons',
                null,
                [
                    'label' => 'backend.admin.is_for_private_lessons',
                    'editable' => true,
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
                    'header_style' => 'width:104px',
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'edit' => ['template' => 'Admin/Buttons/list__action_edit_button.html.twig'],
                        'emails' => ['template' => 'Admin/Cells/list__action_group_emails.html.twig'],
                    ],
                ]
            )
        ;
    }

    public function configureExportFields(): array
    {
        return [
            'code',
            'name',
            'book',
            'color',
            'isForPrivateLessons',
            'enabled',
        ];
    }
}
