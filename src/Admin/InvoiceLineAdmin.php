<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

final class InvoiceLineAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'InvoiceLine';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::ASC;
        $sortValues[DatagridInterface::SORT_BY] = 'description';
    }

    public function generateBaseRoutePattern(bool $isChildAdmin = false): string
    {
        return 'billings/invoice-line';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray('backend.admin.general'))
            ->add(
                'description',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.description',
                ]
            )
            ->add(
                'units',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.units',
                ]
            )
            ->add(
                'priceUnit',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.priceUnit',
                ]
            )
            ->add(
                'discount',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.discount',
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls'))
            ->add(
                'invoice',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.invoice',
                    'attr' => [
                        'hidden' => true,
                    ],
                    'required' => true,
                ]
            )
            ->add(
                'enabled',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.enabled',
                    'required' => false,
                    'attr' => [
                        'hidden' => true,
                    ],
                ]
            )
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'invoice',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.invoice',
                ]
            )
            ->add(
                'description',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.description',
                ]
            )
            ->add(
                'units',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.units',
                ]
            )
            ->add(
                'priceUnit',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.priceUnit',
                ]
            )
            ->add(
                'discount',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.discount',
                ]
            )
            ->add(
                'total',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.total',
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
                'invoice',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.invoice',
                    'editable' => true,
                ]
            )
            ->add(
                'description',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.description',
                    'editable' => true,
                ]
            )
            ->add(
                'units',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.units',
                    'editable' => true,
                ]
            )
            ->add(
                'priceUnit',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.priceUnit',
                    'editable' => true,
                ]
            )
            ->add(
                'discount',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.discount',
                    'editable' => true,
                ]
            )
            ->add(
                'total',
                null,
                [
                    'label' => 'backend.admin.invoiceLine.total',
                    'editable' => true,
                ]
            )
            ->add(
                'enabled',
                null,
                [
                    'label' => 'backend.admin.enabled',
                    'editable' => true,
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
}
