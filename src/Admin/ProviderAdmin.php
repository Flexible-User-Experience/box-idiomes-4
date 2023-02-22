<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\City;
use App\Enum\StudentPaymentEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class ProviderAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Provider';
    protected $baseRoutePattern = 'purchases/provider';

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
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray('backend.admin.general', 5))
            ->add(
                'tic',
                null,
                [
                    'label' => 'backend.admin.customer.tic',
                    'required' => true,
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.customer.name',
                    'required' => true,
                ]
            )
            ->add(
                'alias',
                null,
                [
                    'label' => 'backend.admin.customer.alias',
                ]
            )
            ->add(
                'address',
                null,
                [
                    'label' => 'backend.admin.customer.address',
                ]
            )
            ->add(
                'city',
                null,
                [
                    'label' => 'backend.admin.customer.city',
                    'query_builder' => $this->em->getRepository(City::class)->getEnabledSortedByNameQB(),
                    'required' => true,
                ]
            )
            ->add(
                'phone',
                null,
                [
                    'label' => 'backend.admin.customer.phone',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.customer.email',
                ]
            )
            ->end()
            ->with('backend.admin.payments', $this->getFormMdSuccessBoxArray('backend.admin.payments', 4))
            ->add(
                'paymentMethod',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.customer.payment_method',
                    'choices' => StudentPaymentEnum::getEnumArray(),
                    'required' => true,
                ]
            )
            ->add(
                'ibanForBankDraftPayment',
                TextType::class,
                [
                    'label' => 'backend.admin.customer.iban_for_bank_draft_payment',
                    'help' => 'backend.admin.customer.iban_for_bank_draft_payment_help',
                    'required' => false,
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls', 3))
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
                'tic',
                null,
                [
                    'label' => 'backend.admin.customer.tic',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.customer.name',
                ]
            )
            ->add(
                'alias',
                null,
                [
                    'label' => 'backend.admin.customer.alias',
                ]
            )
            ->add(
                'address',
                null,
                [
                    'label' => 'backend.admin.customer.address',
                ]
            )
            ->add(
                'city',
                null,
                [
                    'label' => 'backend.admin.customer.city',
                ]
            )
            ->add(
                'phone',
                null,
                [
                    'label' => 'backend.admin.customer.phone',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.customer.email',
                ]
            )
            ->add(
                'paymentMethod',
                null,
                [
                    'label' => 'backend.admin.customer.payment_method',
                ]
            )
            ->add(
                'ibanForBankDraftPayment',
                null,
                [
                    'label' => 'backend.admin.customer.iban_for_bank_draft_payment',
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
                'tic',
                null,
                [
                    'label' => 'backend.admin.customer.tic',
                    'editable' => true,
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.customer.name',
                    'editable' => true,
                ]
            )
            ->add(
                'alias',
                null,
                [
                    'label' => 'backend.admin.customer.alias',
                    'editable' => true,
                ]
            )
            ->add(
                'city',
                null,
                [
                    'label' => 'backend.admin.customer.city',
                    'editable' => true,
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.customer.email',
                    'editable' => true,
                ]
            )
            ->add(
                'enabled',
                null,
                [
                    'label' => 'backend.admin.enabled',
                    'editable' => true,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                ListMapper::NAME_ACTIONS,
                null,
                [
                    'label' => 'backend.admin.actions',
                    'header_style' => 'width:63px',
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
            'tic',
            'name',
            'alias',
            'address',
            'city',
            'phone',
            'email',
            'paymentString',
            'ibanForBankDraftPayment',
            'enabled',
        ];
    }
}
