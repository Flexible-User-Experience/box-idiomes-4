<?php

namespace App\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class BankCreditorSepaAdmin.
 *
 * @category Admin
 */
class BankCreditorSepaAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'BankCreditorSepa';
    protected $baseRoutePattern = 'administrations/bank-creditor-sepa';
    protected $datagridValues = array(
        '_sort_by' => 'iban',
        '_sort_order' => 'asc',
    );

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.bank.name',
                )
            )
            ->add(
                'swiftCode',
                null,
                array(
                    'label' => 'backend.admin.bank.swiftCode',
                    'required' => false,
                )
            )
            ->add(
                'accountNumber',
                null,
                array(
                    'label' => 'IBAN',
                    'required' => true,
                    'help' => 'backend.admin.bank.accountNumber_help',
                )
            )
            ->end()
        ;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.bank.name',
                )
            )
            ->add(
                'accountNumber',
                null,
                array(
                    'label' => 'backend.admin.bank.accountNumber',
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'backend.admin.enabled',
                )
            )
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add(
                'organizationId',
                null,
                array(
                    'label' => 'backend.admin.bank.name',
                    'editable' => true,
                )
            )
            ->add(
                'creditorName',
                null,
                array(
                    'label' => 'backend.admin.bank.name',
                    'editable' => true,
                )
            )
            ->add(
                'iban',
                null,
                array(
                    'label' => 'backend.admin.bank.name',
                    'editable' => true,
                )
            )
            ->add(
                'bic',
                null,
                array(
                    'label' => 'backend.admin.bank.name',
                    'editable' => true,
                )
            )
            ->add(
                'enabled',
                null,
                array(
                    'label' => 'backend.admin.enabled',
                    'editable' => true,
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => array(
                        'edit' => array('template' => 'Admin/Buttons/list__action_edit_button.html.twig'),
                        'delete' => array('template' => 'Admin/Buttons/list__action_delete_button.html.twig'),
                    ),
                    'label' => 'Accions',
                )
            )
        ;
    }
}
