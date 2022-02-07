<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Enum\PreRegisterSeasonEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class PreRegisterAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'PreRegister';
    protected $baseRoutePattern = 'students/pre-register';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::DESC;
        $sortValues[DatagridInterface::SORT_BY] = 'createdAt';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('student', $this->getRouterIdParameter().'/create-student')
            ->remove('create')
            ->remove('edit')
        ;
    }

    public function configureBatchActions(array $actions): array
    {
        if ($this->hasRoute('show') && $this->hasAccess('show')) {
            $actions['generatestudents'] = [
                'label' => 'backend.admin.pre_register.batch_action',
                'translation_domain' => 'messages',
                'ask_confirmation' => false,
            ];
        }

        return $actions;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'createdAt',
                DateFilter::class,
                [
                    'label' => 'frontend.forms.preregister.date',
                    'field_type' => DatePickerType::class,
                    'field_options' => [
                        'widget' => 'single_text',
                        'format' => 'dd-MM-yyyy',
                    ],
//                    'format' => 'd-m-Y',
                ]
            )
            ->add(
                'season',
                null,
                [
                    'label' => 'frontend.forms.preregister.season',
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'choices' => PreRegisterSeasonEnum::getEnumArray(),
                        'expanded' => false,
                        'multiple' => false,
                    ],
//                    'format' => 'd-m-Y',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'frontend.forms.preregister.name',
                ]
            )
            ->add(
                'surname',
                null,
                [
                    'label' => 'frontend.forms.preregister.surname',
                ]
            )
            ->add(
                'phone',
                null,
                [
                    'label' => 'frontend.forms.preregister.phone',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'frontend.forms.preregister.email',
                ]
            )
            ->add(
                'age',
                null,
                [
                    'label' => 'frontend.forms.preregister.age',
                ]
            )
            ->add(
                'courseLevel',
                null,
                [
                    'label' => 'frontend.forms.preregister.course_level',
                ]
            )
            ->add(
                'preferredTimetable',
                null,
                [
                    'label' => 'frontend.forms.preregister.preferred_timetable',
                ]
            )
            ->add(
                'previousAcademy',
                null,
                [
                    'label' => 'frontend.forms.preregister.previous_academy',
                ]
            )
            ->add(
                'comments',
                null,
                [
                    'label' => 'frontend.forms.preregister.comments',
                ]
            )
            ->add(
                'hasBeenPreviousCustomer',
                null,
                [
                    'label' => 'frontend.forms.preregister.has_been_previous_customer_short',
                ]
            )
            ->add(
                'wantsToMakeOfficialExam',
                null,
                [
                    'label' => 'frontend.forms.preregister.wants_to_make_official_exam_short',
                ]
            )
            ->add(
                'enabled',
                null,
                [
                    'label' => 'frontend.forms.preregister.enabled',
                ]
            )
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add(
                'createdAt',
                null,
                [
                    'label' => 'frontend.forms.preregister.date',
                    'format' => 'd/m/Y H:i',
                ]
            )
            ->add(
                'season',
                null,
                [
                    'label' => 'frontend.forms.preregister.season',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'frontend.forms.preregister.name',
                ]
            )
            ->add(
                'surname',
                null,
                [
                    'label' => 'frontend.forms.preregister.surname',
                ]
            )
            ->add(
                'phone',
                null,
                [
                    'label' => 'frontend.forms.preregister.phone',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'frontend.forms.preregister.email',
                ]
            )
            ->add(
                'age',
                null,
                [
                    'label' => 'frontend.forms.preregister.age',
                ]
            )
            ->add(
                'courseLevel',
                null,
                [
                    'label' => 'frontend.forms.preregister.course_level',
                ]
            )
            ->add(
                'preferredTimetable',
                null,
                [
                    'label' => 'frontend.forms.preregister.preferred_timetable',
                ]
            )
            ->add(
                'previousAcademy',
                null,
                [
                    'label' => 'frontend.forms.preregister.previous_academy',
                ]
            )
            ->add(
                'comments',
                null,
                [
                    'label' => 'frontend.forms.preregister.comments',
                ]
            )
            ->add(
                'hasBeenPreviousCustomer',
                null,
                [
                    'label' => 'frontend.forms.preregister.has_been_previous_customer_short',
                ]
            )
            ->add(
                'wantsToMakeOfficialExam',
                null,
                [
                    'label' => 'frontend.forms.preregister.wants_to_make_official_exam_short',
                ]
            )
            ->add(
                'enabled',
                null,
                [
                    'label' => 'frontend.forms.preregister.enabled',
                ]
            )
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add(
                'createdAt',
                null,
                [
                    'label' => 'frontend.forms.preregister.date',
                    'editable' => false,
                    'format' => 'd/m/Y H:i',
                ]
            )
            ->add(
                'season',
                null,
                [
                    'label' => 'frontend.forms.preregister.season',
                    'template' => 'Admin/Cells/list__cell_pre_register_season.html.twig',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'frontend.forms.preregister.name',
                    'editable' => false,
                ]
            )
            ->add(
                'surname',
                null,
                [
                    'label' => 'frontend.forms.preregister.surname',
                    'editable' => false,
                ]
            )
            ->add(
                'phone',
                null,
                [
                    'label' => 'frontend.forms.preregister.phone',
                    'editable' => false,
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'frontend.forms.preregister.email',
                    'editable' => false,
                ]
            )
            ->add(
                'enabled',
                null,
                [
                    'label' => 'frontend.forms.preregister.enabled',
                    'editable' => false,
                ]
            )
            ->add(
                ListMapper::NAME_ACTIONS,
                null,
                [
                    'label' => 'backend.admin.actions',
                    'actions' => [
                        'show' => [
                            'template' => 'Admin/Buttons/list__action_show_button.html.twig',
                        ],
                        'student' => [
                            'template' => 'Admin/Buttons/list__action_create_student_from_pre_register_button.html.twig',
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
            'createdAtString',
            'seasonString',
            'name',
            'surname',
            'phone',
            'email',
            'age',
            'courseLevel',
            'preferredTimetable',
            'previousAcademy',
            'comments',
            'hasBeenPreviousCustomerString',
            'wantsToMakeOfficialExamString',
            'enabled',
        ];
    }
}
