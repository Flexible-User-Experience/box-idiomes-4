<?php

namespace App\Admin;

use App\Enum\TeacherColorEnum;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Class TeacherAdmin.
 *
 * @category Admin
 */
class TeacherAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Teacher';
    protected $baseRoutePattern = 'teachers/teacher';
    protected $datagridValues = [
        '_sort_by' => 'position',
        '_sort_order' => 'asc',
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        parent::configureRoutes($collection);
        $collection
            ->remove('delete')
            ->add('detail', $this->getRouterIdParameter().'/detail')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with('backend.admin.image', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'imageFile',
                FileType::class,
                [
                    'label' => 'backend.admin.image',
                    'help' => $this->getImageHelperFormMapperWithThumbnailAspectRatio(),
                    'required' => false,
                ]
            )
            ->end()
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(6))
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
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(2))
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

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
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

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add(
                'position',
                'decimal',
                [
                    'label' => 'backend.admin.position',
                    'editable' => true,
                ]
            )
            ->add(
                'image',
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
                    'header_class' => 'text-center',
                    'row_align' => 'center',
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
                '_action',
                'actions',
                [
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'edit' => ['template' => 'Admin/Buttons/list__action_edit_button.html.twig'],
                        'detail' => [
                            'template' => 'Admin/Cells/list__action_teacher_detail.html.twig',
                        ],
                    ],
                    'label' => 'backend.admin.actions',
                ]
            )
        ;
    }

    public function getExportFields(): array
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
