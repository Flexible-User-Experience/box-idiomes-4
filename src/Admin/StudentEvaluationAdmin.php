<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\Student;
use App\Enum\StudentEvaluationEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\DoctrineORMAdminBundle\Filter\ModelFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
            ->add(
                'course',
                NumberType::class,
                [
                    'label' => 'backend.admin.student_evaluation.course',
                    'required' => true,
                    'html5' => true,
                    'help' => 'backend.admin.student_evaluation.course_helper',
                ]
            )
            ->add(
                'evaluation',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.student_evaluation.evaluation',
                    'choices' => StudentEvaluationEnum::getEnumArray(),
                    'required' => true,
                ]
            )
            ->end()
            ->with('backend.admin.evaluation', $this->getFormMdSuccessBoxArray('backend.admin.controls', 4))
            ->add(
                'writting',
                TextType::class,
                [
                    'label' => 'backend.admin.student_evaluation.writting',
                    'required' => false,
                ]
            )
            ->add(
                'reading',
                TextType::class,
                [
                    'label' => 'backend.admin.student_evaluation.reading',
                    'required' => false,
                ]
            )
            ->add(
                'useOfEnglish',
                TextType::class,
                [
                    'label' => 'backend.admin.student_evaluation.use_of_english',
                    'required' => false,
                ]
            )
            ->add(
                'listening',
                TextType::class,
                [
                    'label' => 'backend.admin.student_evaluation.listening',
                    'required' => false,
                ]
            )
            ->add(
                'speaking',
                TextType::class,
                [
                    'label' => 'backend.admin.student_evaluation.speaking',
                    'required' => false,
                ]
            )
            ->add(
                'behaviour',
                TextType::class,
                [
                    'label' => 'backend.admin.student_evaluation.behaviour',
                    'required' => false,
                ]
            )
            ->add(
                'comments',
                TextareaType::class,
                [
                    'label' => 'backend.admin.student_evaluation.coments',
                    'required' => false,
                    'attr' => [
                        'rows' => 5,
                    ],
                ]
            )
            ->add(
                'globalMark',
                TextType::class,
                [
                    'label' => 'backend.admin.student_evaluation.global_mark',
                    'required' => false,
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
