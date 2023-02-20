<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\City;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

final class TrainingCenterAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'TrainingCenter';
    protected $baseRoutePattern = 'classrooms/training-center';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::ASC;
        $sortValues[DatagridInterface::SORT_BY] = 'name';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection->remove('delete');
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray('backend.admin.general', 4))
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.training_center.name',
                ]
            )
            ->add(
                'address',
                null,
                [
                    'label' => 'backend.admin.training_center.address',
                    'required' => false,
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls', 3))
            ->add(
                'city',
                EntityType::class,
                [
                    'label' => 'backend.admin.training_center.city',
                    'required' => true,
                    'class' => City::class,
                    'choice_label' => 'name',
                    'query_builder' => $this->em->getRepository(City::class)->getEnabledSortedByNameQB(),
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
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.training_center.name',
                ]
            )
            ->add(
                'address',
                null,
                [
                    'label' => 'backend.admin.training_center.address',
                ]
            )
            ->add(
                'city',
                null,
                [
                    'label' => 'backend.admin.training_center.city',
                    'field_type' => EntityType::class,
                    'field_options' => [
                        'class' => City::class,
                        'query_builder' => $this->em->getRepository(City::class)->getEnabledSortedByNameQB(),
                    ],
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
                'name',
                null,
                [
                    'label' => 'backend.admin.training_center.name',
                    'editable' => true,
                ]
            )
            ->add(
                'address',
                null,
                [
                    'label' => 'backend.admin.training_center.address',
                    'editable' => true,
                ]
            )
            ->add(
                'city',
                null,
                [
                    'label' => 'backend.admin.training_center.city',
                    'editable' => false,
                ]
            )
            ->add(
                'enabled',
                null,
                [
                    'label' => 'backend.admin.enabled',
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                    'editable' => true,
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
            'name',
        ];
    }
}
