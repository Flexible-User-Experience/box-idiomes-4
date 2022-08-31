<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Enum\TariffTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class TariffAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Tarriff';
    protected $baseRoutePattern = 'billings/tariff';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::DESC;
        $sortValues[DatagridInterface::SORT_BY] = 'year';
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
                'year',
                null,
                [
                    'label' => 'backend.admin.tariff.year',
                    'required' => true,
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.tariff.name',
                    'required' => false,
                ]
            )
            ->add(
                'price',
                null,
                [
                    'label' => 'backend.admin.tariff.price',
                    'required' => true,
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls', 3))
            ->add(
                'type',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.teacher_absence.type',
                    'choices' => TariffTypeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                ]
            )
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'year',
                null,
                [
                    'label' => 'backend.admin.tariff.year',
                ]
            )
            ->add(
                'type',
                null,
                [
                    'label' => 'backend.admin.tariff.type',
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'expanded' => false,
                        'multiple' => false,
                        'choices' => TariffTypeEnum::getEnumArray(),
                    ],
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.tariff.name',
                ]
            )
            ->add(
                'price',
                null,
                [
                    'label' => 'backend.admin.tariff.price',
                ]
            )
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add(
                'year',
                null,
                [
                    'label' => 'backend.admin.tariff.year',
                    'editable' => true,
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                ]
            )
            ->add(
                'type',
                null,
                [
                    'label' => 'backend.admin.tariff.type',
                    'template' => 'Admin/Cells/list__cell_tariff_type.html.twig',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.tariff.name',
                    'editable' => true,
                ]
            )
            ->add(
                'price',
                null,
                [
                    'label' => 'backend.admin.tariff.price',
                    'template' => 'Admin/Cells/list__cell_tariff_price.html.twig',
                    'editable' => false,
                    'header_class' => 'text-right',
                    'row_align' => 'right',
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
            'year',
            'typeString',
            'name',
            'price',
        ];
    }
}
