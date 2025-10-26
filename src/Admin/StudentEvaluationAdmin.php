<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\Student;
use App\Entity\StudentEvaluation;
use App\Enum\ReceiptYearMonthEnum;
use App\Enum\StudentEvaluationEnum;
use App\Repository\StudentRepository;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\DoctrineORMAdminBundle\Filter\ModelFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class StudentEvaluationAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'StudentEvaluation';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::DESC;
        $sortValues[DatagridInterface::SORT_BY] = 'course';
    }

    public function generateBaseRoutePattern(bool $isChildAdmin = false): string
    {
        return 'students/evaluation';
    }

    protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
    {
        $query = parent::configureQuery($query);
        $rootAlias = current($query->getRootAliases());
        $query
            ->leftJoin(sprintf('%s.student', $rootAlias), StudentRepository::ALIAS)
            ->addOrderBy(sprintf('%s.evaluation', $rootAlias), SortOrderTypeEnum::ASC)
            ->addOrderBy(sprintf('%s.surname', StudentRepository::ALIAS), SortOrderTypeEnum::ASC)
            ->addOrderBy(sprintf('%s.name', StudentRepository::ALIAS), SortOrderTypeEnum::ASC)
        ;

        return $query;
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
                ChoiceType::class,
                [
                    'label' => 'backend.admin.student_evaluation.course',
                    'choices' => ReceiptYearMonthEnum::getReversedYearAsFullCourseEnumArray(),
                    'required' => true,
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
            ->with('backend.admin.evaluation', $this->getFormMdSuccessBoxArray('backend.admin.evaluation', 4))
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
                    'label' => 'backend.admin.student_evaluation.comments',
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
                'course',
                null,
                [
                    'label' => 'backend.admin.student_evaluation.course',
                    'required' => true,
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'choices' => ReceiptYearMonthEnum::getReversedYearAsFullCourseEnumArray(),
                        'expanded' => false,
                        'multiple' => false,
                    ],
                ]
            )
            ->add(
                'evaluation',
                null,
                [
                    'label' => 'backend.admin.student_evaluation.evaluation',
                    'required' => true,
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'choices' => StudentEvaluationEnum::getEnumArray(),
                        'expanded' => false,
                        'multiple' => false,
                    ],
                ]
            )
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
                'writting',
                null,
                [
                    'label' => 'backend.admin.student_evaluation.writting',
                ]
            )
            ->add(
                'reading',
                null,
                [
                    'label' => 'backend.admin.student_evaluation.reading',
                ]
            )
            ->add(
                'useOfEnglish',
                null,
                [
                    'label' => 'backend.admin.student_evaluation.use_of_english',
                ]
            )
            ->add(
                'listening',
                null,
                [
                    'label' => 'backend.admin.student_evaluation.listening',
                ]
            )
            ->add(
                'speaking',
                null,
                [
                    'label' => 'backend.admin.student_evaluation.speaking',
                ]
            )
            ->add(
                'behaviour',
                null,
                [
                    'label' => 'backend.admin.student_evaluation.behaviour',
                ]
            )
            ->add(
                'comments',
                null,
                [
                    'label' => 'backend.admin.student_evaluation.comments',
                ]
            )
            ->add(
                'globalMark',
                null,
                [
                    'label' => 'backend.admin.student_evaluation.global_mark',
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
                'course',
                null,
                [
                    'label' => 'backend.admin.student_evaluation.course',
                    'accessor' => 'fullCourseAsString',
                    'editable' => false,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'evaluation',
                FieldDescriptionInterface::TYPE_TRANS,
                [
                    'label' => 'backend.admin.student_evaluation.evaluation',
                    'accessor' => function (StudentEvaluation $subject) {
                        return StudentEvaluationEnum::getReversedEnumArray()[$subject->getEvaluation()];
                    },
                    'editable' => false,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
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
