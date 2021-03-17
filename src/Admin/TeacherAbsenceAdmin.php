<?php

namespace App\Admin;

use App\Entity\Teacher;
use App\Enum\TeacherAbsenceTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class TeacherAbsenceAdmin.
 *
 * @category Admin
 */
class TeacherAbsenceAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'TeacherAbsence';
    protected $baseRoutePattern = 'teachers/absence';
    protected $datagridValues = [
        '_sort_by' => 'day',
        '_sort_order' => 'desc',
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        parent::configureRoutes($collection);
        $collection->remove('delete');
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'day',
                DatePickerType::class,
                [
                    'label' => 'backend.admin.teacher_absence.day',
                    'format' => 'd/M/y',
                    'required' => true,
                ]
            )
            ->add(
                'type',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.teacher_absence.type',
                    'choices' => TeacherAbsenceTypeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                ]
            )
            ->add(
                'teacher',
                EntityType::class,
                [
                    'label' => 'backend.admin.teacher_absence.teacher',
                    'required' => true,
                    'class' => Teacher::class,
                    'choice_label' => 'name',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.teacher_repository')->getEnabledSortedByNameQB(),
                ]
            )
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add(
                'day',
                DateFilter::class,
                [
                    'label' => 'backend.admin.teacher_absence.day',
                    'field_type' => DatePickerType::class,
                    'format' => 'd-m-Y',
                ],
                null,
                [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                ]
            )
            ->add(
                'type',
                null,
                [
                    'label' => 'backend.admin.teacher_absence.type',
                ],
                ChoiceType::class,
                [
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => TeacherAbsenceTypeEnum::getEnumArray(),
                ]
            )
            ->add(
                'teacher',
                null,
                [
                    'label' => 'backend.admin.teacher_absence.teacher',
                ]
            )
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add(
                'image',
                null,
                [
                    'label' => 'backend.admin.image',
                    'template' => 'Admin/Cells/list__cell_teacher_absence_image_field.html.twig',
                ]
            )
            ->add(
                'teacher',
                null,
                [
                    'label' => 'backend.admin.teacher_absence.teacher',
                    'editable' => false,
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => ['fieldName' => 'name'],
                    'sort_parent_association_mappings' => [['fieldName' => 'teacher']],
                ]
            )
            ->add(
                'day',
                'date',
                [
                    'label' => 'backend.admin.teacher_absence.day',
                    'format' => 'd/m/Y',
                    'editable' => true,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'type',
                null,
                [
                    'label' => 'backend.admin.teacher_absence.type',
                    'template' => 'Admin/Cells/list__cell_teacher_absence_type.html.twig',
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
                    ],
                    'label' => 'backend.admin.actions',
                ]
            )
        ;
    }

    public function getExportFields(): array
    {
        return [
            'dayString',
            'typeString',
            'teacher',
        ];
    }
}
