<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\Student;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\DoctrineORMAdminBundle\Filter\ModelFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

final class StudentEvaluationAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'StudentEvaluation';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::DESC;
        $sortValues[DatagridInterface::SORT_BY] = 'day';
    }

    public function generateBaseRoutePattern(bool $isChildAdmin = false): string
    {
        return 'students/evaluation';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray('backend.admin.general', 3))
            ->add(
                'student',
                EntityType::class,
                [
                    'label' => 'backend.admin.student.student',
                    'required' => true,
                    'class' => Student::class,
                    'choice_label' => 'getFullCanonicalName',
                    'query_builder' => $this->em->getRepository(Student::class)->getEnabledSortedBySurnameQB(),
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls', 3))
            ->add(
                'hasBeenNotified',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.student.has_been_notified',
                    'required' => false,
                    'disabled' => true,
                ]
            )
            ->add(
                'notificationDate',
                DatePickerType::class,
                [
                    'label' => 'backend.admin.student.notification_date',
                    'format' => 'd/M/y H:m',
                    'required' => false,
                    'disabled' => true,
                ]
            )
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'student',
                ModelFilter::class,
                [
                    'label' => 'backend.admin.invoice.student',
                    'field_type' => ModelAutocompleteType::class,
                    'field_options' => [
                        'class' => Student::class,
                        'property' => ['name', 'surname'],
                    ],
                ]
            )
            ->add(
                'hasBeenNotified',
                null,
                [
                    'label' => 'backend.admin.student.has_been_notified',
                    'editable' => false,
                ]
            )
            ->add(
                'notificationDate',
                null,
                [
                    'label' => 'backend.admin.student.notification_date',
                    'field_type' => DatePickerType::class,
                    'field_options' => [
                        'widget' => 'single_text',
                        'format' => 'dd-MM-yyyy',
                    ],
                ]
            )
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add(
                'student',
                null,
                [
                    'label' => 'backend.admin.student.student',
                    'editable' => false,
                    'associated_property' => 'getFullCanonicalName',
                    'sortable' => true,
                    'sort_field_mapping' => ['fieldName' => 'name'],
                    'sort_parent_association_mappings' => [['fieldName' => 'student']],
                ]
            )
            ->add(
                'hasBeenNotified',
                null,
                [
                    'label' => 'backend.admin.student.has_been_notified',
                    'editable' => false,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'notificationDate',
                'date',
                [
                    'label' => 'backend.admin.student.notification_date',
                    'format' => 'd/m/Y H:i',
                    'editable' => false,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                ListMapper::NAME_ACTIONS,
                null,
                [
                    'label' => 'backend.admin.actions',
                    'header_style' => 'width:116px',
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
            'student',
            'hasBeenNotified',
            'notificationDateString',
        ];
    }
}
