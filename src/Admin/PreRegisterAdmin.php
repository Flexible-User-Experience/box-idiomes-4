<?php

namespace App\Admin;

use App\Enum\PreRegisterSeasonEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class PreRegisterAdmin.
 *
 * @category Admin
 */
class PreRegisterAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Preregisters';
    protected $baseRoutePattern = 'students/pre-register';
    protected $datagridValues = array(
        '_sort_by' => 'createdAt',
        '_sort_order' => 'desc',
    );

    /**
     * Configure route collection.
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('student', $this->getRouterIdParameter().'/create-student')
            ->remove('create')
            ->remove('edit')
            ->remove('delete')
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add(
                'createdAt',
                'doctrine_orm_date',
                array(
                    'label' => 'frontend.forms.preregister.date',
                    'field_type' => DatePickerType::class,
                    'format' => 'd-m-Y',
                ),
                null,
                array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                )
            )
            ->add(
                'season',
                null,
                array(
                    'label' => 'frontend.forms.preregister.season',
                    'field_type' => DatePickerType::class,
                    'format' => 'd-m-Y',
                ),
                ChoiceType::class,
                array(
                    'choices' => PreRegisterSeasonEnum::getEnumArray(),
                    'expanded' => false,
                    'multiple' => false,
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'frontend.forms.preregister.name',
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'frontend.forms.preregister.surname',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'frontend.forms.preregister.phone',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'frontend.forms.preregister.email',
                )
            )
            ->add(
                'age',
                null,
                array(
                    'label' => 'frontend.forms.preregister.age',
                )
            )
            ->add(
                'courseLevel',
                null,
                array(
                    'label' => 'frontend.forms.preregister.course_level',
                )
            )
            ->add(
                'preferredTimetable',
                null,
                array(
                    'label' => 'frontend.forms.preregister.preferred_timetable',
                )
            )
            ->add(
                'previousAcademy',
                null,
                array(
                    'label' => 'frontend.forms.preregister.previous_academy',
                )
            )
            ->add(
                'comments',
                null,
                array(
                    'label' => 'frontend.forms.preregister.comments',
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'frontend.forms.preregister.enabled',
                )
            )
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add(
                'createdAt',
                null,
                array(
                    'label' => 'frontend.forms.preregister.date',
                    'format' => 'd/m/Y H:i'
                )
            )
            ->add(
                'season',
                null,
                array(
                    'label' => 'frontend.forms.preregister.season',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'frontend.forms.preregister.name',
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'frontend.forms.preregister.surname',
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'frontend.forms.preregister.phone',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'frontend.forms.preregister.email',
                )
            )
            ->add(
                'age',
                null,
                array(
                    'label' => 'frontend.forms.preregister.age',
                )
            )
            ->add(
                'courseLevel',
                null,
                array(
                    'label' => 'frontend.forms.preregister.course_level',
                )
            )
            ->add(
                'preferredTimetable',
                null,
                array(
                    'label' => 'frontend.forms.preregister.preferred_timetable',
                )
            )
            ->add(
                'previousAcademy',
                null,
                array(
                    'label' => 'frontend.forms.preregister.previous_academy',
                )
            )
            ->add(
                'comments',
                null,
                array(
                    'label' => 'frontend.forms.preregister.comments',
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'frontend.forms.preregister.enabled',
                )
            )
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->add(
                'createdAt',
                null,
                array(
                    'label' => 'frontend.forms.preregister.date',
                    'editable' => false,
                    'format' => 'd/m/Y H:i'
                )
            )
            ->add(
                'season',
                null,
                array(
                    'label' => 'frontend.forms.preregister.season',
                    'template' => 'Admin/Cells/list__cell_pre_register_season.html.twig',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'frontend.forms.preregister.name',
                    'editable' => false,
                )
            )
            ->add(
                'surname',
                null,
                array(
                    'label' => 'frontend.forms.preregister.surname',
                    'editable' => false,
                )
            )
            ->add(
                'phone',
                null,
                array(
                    'label' => 'frontend.forms.preregister.phone',
                    'editable' => false,
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'frontend.forms.preregister.email',
                    'editable' => false,
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'frontend.forms.preregister.enabled',
                    'editable' => false,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'show' => array('template' => 'Admin/Buttons/list__action_show_button.html.twig'),
                    ),
                    'label' => 'Accions',
                )
            )
        ;
    }

    /**
     * @return array
     */
    public function getExportFields()
    {
        return array(
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
        );
    }
}
