<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Enum\TeacherColorEnum;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class TeacherAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Teacher';
    protected $baseRoutePattern = 'teachers/teacher';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::ASC;
        $sortValues[DatagridInterface::SORT_BY] = 'position';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection
            ->add('detail', $this->getRouterIdParameter().'/detail')
            ->remove('delete')
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('backend.admin.image', $this->getFormMdSuccessBoxArray('backend.admin.image', 4))
            ->add(
                'imageFile',
                VichImageType::class,
                [
                    'label' => 'backend.admin.image',
                    'required' => false,
                ]
            )
            ->end()
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray('backend.admin.general'))
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.teacher.name',
                ]
            )
            ->add(
                'description',
                CKEditorType::class,
                [
                    'label' => 'backend.admin.description',
                    'config_name' => 'my_config',
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls', 2))
            ->add(
                'position',
                null,
                [
                    'label' => 'backend.admin.position',
                ]
            )
            ->add(
                'color',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.teacher.color',
                    'choices' => TeacherColorEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                ]
            )
            ->add(
                'showInHomepage',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.teacher.showInHomepage',
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
                'name',
                null,
                [
                    'label' => 'backend.admin.teacher.name',
                ]
            )
            ->add(
                'description',
                null,
                [
                    'label' => 'backend.admin.description',
                ]
            )
            ->add(
                'showInHomepage',
                null,
                [
                    'label' => 'backend.admin.teacher.showInHomepage',
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
                'position',
                null,
                [
                    'label' => 'backend.admin.position',
                    'editable' => true,
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                ]
            )
            ->add(
                'imageName',
                null,
                [
                    'label' => 'backend.admin.image',
                    'template' => 'Admin/Cells/list__cell_image_field.html.twig',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.teacher.name',
                    'editable' => true,
                ]
            )
            ->add(
                'color',
                null,
                [
                    'label' => 'backend.admin.teacher.color',
                    'template' => 'Admin/Cells/list__cell_teacher_color.html.twig',
                ]
            )
            ->add(
                'showInHomepage',
                null,
                [
                    'label' => 'backend.admin.teacher.showInHomepage',
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
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'edit' => [
                            'template' => 'Admin/Buttons/list__action_edit_button.html.twig',
                        ],
                        'detail' => [
                            'template' => 'Admin/Cells/list__action_teacher_detail.html.twig',
                        ],
                    ],
                ]
            )
        ;
    }

    public function configureExportFields(): array
    {
        return [
            'name',
            'position',
            'color',
            'description',
            'showInHomepage',
            'enabled',
        ];
    }
}
