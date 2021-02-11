<?php

namespace App\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class ReceiptLineAdmin.
 *
 * @category Admin
 */
class ReceiptLineAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'ReceiptLine';
    protected $baseRoutePattern = 'billings/receipt-line';
    protected $datagridValues = [
        '_sort_by' => 'description',
        '_sort_order' => 'asc',
    ];

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(6))
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
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'receipt',
                null,
                [
                    'label' => 'backend.admin.receiptLine.receipt',
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

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add(
                'receipt',
                null,
                [
                    'label' => 'backend.admin.receiptLine.receipt',
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

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add(
                'receipt',
                null,
                [
                    'label' => 'backend.admin.receiptLine.receipt',
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
                '_action',
                'actions',
                [
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'show' => ['template' => 'Admin/Buttons/list__action_show_button.html.twig'],
                        'edit' => ['template' => 'Admin/Buttons/list__action_edit_button.html.twig'],
                        'delete' => ['template' => 'Admin/Buttons/list__action_delete_button.html.twig'],
                    ],
                    'label' => 'backend.admin.actions',
                ]
            )
        ;
    }
}
