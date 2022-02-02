<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

final class BankCreditorSepaAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'BankCreditorSepa';
    protected $baseRoutePattern = 'administrations/bank-creditor-sepa';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::ASC;
        $sortValues[DatagridInterface::SORT_BY] = 'name';
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray('backend.admin.general'))
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

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
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

    protected function configureListFields(ListMapper $list): void
    {
        $list
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
                        'edit' => ['template' => 'Admin/Buttons/list__action_edit_button.html.twig'],
                        'delete' => ['template' => 'Admin/Buttons/list__action_delete_button.html.twig'],
                    ],
                ]
            )
        ;
    }
}
