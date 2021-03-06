<?php

namespace App\Admin;

use App\Enum\TariffTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class TariffAdmin.
 *
 * @category Admin
 */
class TariffAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Tarriff';
    protected $baseRoutePattern = 'billings/tariff';
    protected $datagridValues = [
        '_sort_by' => 'year',
        '_sort_order' => 'desc',
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        parent::configureRoutes($collection);
        $collection
            ->remove('delete')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(3))
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
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
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

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
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
                ],
                ChoiceType::class,
                [
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => TariffTypeEnum::getEnumArray(),
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

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
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
                'decimal',
                [
                    'label' => 'backend.admin.tariff.price',
                    'editable' => true,
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
            'year',
            'name',
            'price',
            'typeString',
        ];
    }
}
