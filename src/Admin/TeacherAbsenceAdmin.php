<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\Teacher;
use App\Enum\TeacherAbsenceTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class TeacherAbsenceAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'TeacherAbsence';
    protected $baseRoutePattern = 'teachers/absence';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::DESC;
        $sortValues[DatagridInterface::SORT_BY] = 'day';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection->remove('delete');
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray('backend.admin.general', 3))
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
                    'query_builder' => $this->em->getRepository(Teacher::class)->getEnabledSortedByNameQB(),
                ]
            )
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'day',
                DateFilter::class,
                [
                    'label' => 'backend.admin.teacher_absence.day',
                    'field_type' => DatePickerType::class,
                    'field_options' => [
                        'widget' => 'single_text',
                        'format' => 'dd-MM-yyyy',
                    ],
                ]
            )
            ->add(
                'type',
                null,
                [
                    'label' => 'backend.admin.teacher_absence.type',
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'expanded' => false,
                        'multiple' => false,
                        'choices' => TeacherAbsenceTypeEnum::getEnumArray(),
                    ],
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

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add(
                'teacher.imageName',
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
                    ],
                ]
            )
        ;
    }

    public function configureExportFields(): array
    {
        return [
            'dayString',
            'typeString',
            'teacher',
        ];
    }
}
