<?php

namespace App\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class BankCreditorSepaAdmin.
 *
 * @category Admin
 */
class BankCreditorSepaAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'BankCreditorSepa';
    protected $baseRoutePattern = 'administrations/bank-creditor-sepa';
    protected $datagridValues = [
        '_sort_by' => 'name',
        '_sort_order' => 'asc',
    ];

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.bank.name',
                    'required' => true,
                ]
            )
            ->add(
                'organizationId',
                null,
                [
                    'label' => 'backend.admin.bank.organization_id',
                    'required' => true,
                    'help' => 'Exemple DNI: 12345678A',
                ]
            )
            ->add(
                'creditorName',
                null,
                [
                    'label' => 'backend.admin.bank.creditor_name',
                    'required' => true,
                ]
            )
            ->add(
                'iban',
                null,
                [
                    'label' => 'IBAN',
                    'required' => true,
                    'help' => 'backend.admin.bank.accountNumber_help',
                ]
            )
            ->add(
                'bic',
                null,
                [
                    'label' => 'BIC',
                    'required' => true,
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

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.bank.name',
                ]
            )
            ->add(
                'organizationId',
                null,
                [
                    'label' => 'backend.admin.bank.organization_id',
                ]
            )
            ->add(
                'creditorName',
                null,
                [
                    'label' => 'backend.admin.bank.creditor_name',
                ]
            )
            ->add(
                'iban',
                null,
                [
                    'label' => 'IBAN',
                ]
            )
            ->add(
                'bic',
                null,
                [
                    'label' => 'BIC',
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
                'name',
                null,
                [
                    'label' => 'backend.admin.bank.name',
                    'editable' => true,
                ]
            )
            ->add(
                'organizationId',
                null,
                [
                    'label' => 'backend.admin.bank.organization_id',
                    'editable' => true,
                ]
            )
            ->add(
                'creditorName',
                null,
                [
                    'label' => 'backend.admin.bank.creditor_name',
                    'editable' => true,
                ]
            )
            ->add(
                'iban',
                null,
                [
                    'label' => 'IBAN',
                    'editable' => true,
                ]
            )
            ->add(
                'bic',
                null,
                [
                    'label' => 'BIC',
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
                        'edit' => ['template' => 'Admin/Buttons/list__action_edit_button.html.twig'],
                        'delete' => ['template' => 'Admin/Buttons/list__action_delete_button.html.twig'],
                    ],
                    'label' => 'Accions',
                ]
            )
        ;
    }
}
