<?php

namespace App\Admin;

use App\Enum\PreRegisterSeasonEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class PreRegisterAdmin.
 *
 * @category Admin
 */
class PreRegisterAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'PreRegister';
    protected $baseRoutePattern = 'students/pre-register';
    protected $datagridValues = [
        '_sort_by' => 'createdAt',
        '_sort_order' => 'desc',
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        $collection
            ->add('student', $this->getRouterIdParameter().'/create-student')
            ->remove('create')
            ->remove('edit')
        ;
    }

    /**
     * @param array $actions
     */
    public function configureBatchActions($actions): array
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

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add(
                'createdAt',
                'doctrine_orm_date',
                [
                    'label' => 'frontend.forms.preregister.date',
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
                'season',
                null,
                [
                    'label' => 'frontend.forms.preregister.season',
                    'field_type' => DatePickerType::class,
                    'format' => 'd-m-Y',
                ],
                ChoiceType::class,
                [
                    'choices' => PreRegisterSeasonEnum::getEnumArray(),
                    'expanded' => false,
                    'multiple' => false,
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
                'enabled',
                null,
                [
                    'label' => 'frontend.forms.preregister.enabled',
                ]
            )
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
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
                'enabled',
                null,
                [
                    'label' => 'frontend.forms.preregister.enabled',
                ]
            )
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add(
                'createdAt',
                null,
                [
                    'label' => 'frontend.forms.preregister.date',
                    'editable' => false,
                    'format' => 'd/m/Y H:i',
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'season',
                null,
                [
                    'label' => 'frontend.forms.preregister.season',
                    'template' => 'Admin/Cells/list__cell_pre_register_season.html.twig',
                    'header_class' => 'text-center',
                    'row_align' => 'center',
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
                        'show' => ['template' => 'Admin/Buttons/list__action_show_button.html.twig'],
                        'student' => ['template' => 'Admin/Buttons/list__action_create_student_from_pre_register_button.html.twig'],
                        'delete' => ['template' => 'Admin/Buttons/list__action_delete_button.html.twig'],
                    ],
                    'label' => 'Accions',
                ]
            )
        ;
    }

    public function getExportFields(): array
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
            'enabled',
        ];
    }
}
