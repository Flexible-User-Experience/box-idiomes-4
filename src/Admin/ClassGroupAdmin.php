<?php

namespace App\Admin;

use KunicMarko\ColorPickerBundle\Form\Type\ColorPickerType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class ClassGroupAdmin.
 *
 * @category Admin
 */
class ClassGroupAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'ClassGroup';
    protected $baseRoutePattern = 'classrooms/group';
    protected $datagridValues = [
        '_sort_by' => 'code',
        '_sort_order' => 'asc',
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        parent::configureRoutes($collection);
        $collection
            ->add('emails', $this->getRouterIdParameter().'/get-group-emails')
            ->remove('delete')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(3))
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
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'color',
                ColorPickerType::class,
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

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
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

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
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
                '_action',
                'actions',
                [
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'edit' => ['template' => 'Admin/Buttons/list__action_edit_button.html.twig'],
                        'emails' => ['template' => 'Admin/Cells/list__action_group_emails.html.twig'],
                    ],
                    'label' => 'backend.admin.actions',
                ]
            )
        ;
    }

    public function getExportFields(): array
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
