<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\BankCreditorSepa;
use App\Entity\City;
use App\Entity\Person;
use App\Enum\StudentPaymentEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\DoctrineORMAdminBundle\Filter\ModelFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

final class PersonAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Person';
    protected $baseRoutePattern = 'students/parent';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::ASC;
        $sortValues[DatagridInterface::SORT_BY] = 'surname';
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
                'name',
                null,
                [
                    'label' => 'backend.admin.parent.name',
                ]
            )
            ->add(
                'surname',
                null,
                [
                    'label' => 'backend.admin.parent.surname',
                ]
            )
            ->end()
            ->with('backend.admin.contact.contact', $this->getFormMdSuccessBoxArray('backend.admin.contact.contact', 3))
        ;
        if ($this->isAdminUser()) {
            $form
                ->add(
                    'phone',
                    null,
                    [
                        'label' => 'backend.admin.parent.phone',
                    ]
                )
                ->add(
                    'email',
                    EmailType::class,
                    [
                        'label' => 'backend.admin.parent.email',
                        'required' => true,
                    ]
                )
            ;
        }
        $form
            ->add(
                'address',
                null,
                [
                    'label' => 'backend.admin.parent.address',
                    'required' => false,
                ]
            )
            ->add(
                'city',
                EntityType::class,
                [
                    'label' => 'backend.admin.parent.city',
                    'required' => true,
                    'class' => City::class,
                    'choice_label' => 'name',
                    'query_builder' => $this->em->getRepository(City::class)->getEnabledSortedByNameQB(),
                ]
            )
            ->end()
            ->with('backend.admin.student.payment_information', $this->getFormMdSuccessBoxArray('backend.admin.student.payment_information', 3))
            ->add(
                'payment',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.student.payment',
                    'choices' => StudentPaymentEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                ]
            )
            ->add(
                'bankCreditorSepa',
                EntityType::class,
                [
                    'label' => 'backend.admin.bank.creditor_bank_name',
                    'help' => 'backend.admin.bank.creditor_bank_name_help',
                    'required' => true,
                    'class' => BankCreditorSepa::class,
                    'query_builder' => $this->em->getRepository(BankCreditorSepa::class)->getEnabledSortedByNameQB(),
                ]
            )
            ->add(
                'bank',
                AdminType::class,
                [
                    'label' => ' ',
                    'required' => false,
                    'btn_add' => false,
                    'by_reference' => false,
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls', 3))
            ->add(
                'students',
                null,
                [
                    'label' => 'backend.admin.parent.students',
                    'required' => false,
                    'disabled' => true,
                ]
            )
            ->add(
                'dni',
                null,
                [
                    'label' => 'backend.admin.parent.dni',
                ]
            )
            ->add(
                'dischargeDate',
                DatePickerType::class,
                [
                    'label' => 'backend.admin.student.dischargeDate',
                    'format' => 'd/M/y',
                    'required' => false,
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
                'dni',
                null,
                [
                    'label' => 'backend.admin.parent.dni',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.parent.name',
                ]
            )
            ->add(
                'surname',
                null,
                [
                    'label' => 'backend.admin.parent.surname',
                ]
            )
        ;
        if ($this->isAdminUser()) {
            $filter
                ->add(
                    'phone',
                    null,
                    [
                        'label' => 'backend.admin.parent.phone',
                    ]
                )
                ->add(
                    'email',
                    null,
                    [
                        'label' => 'backend.admin.parent.email',
                    ]
                )
            ;
        }
        $filter
            ->add(
                'address',
                null,
                [
                    'label' => 'backend.admin.parent.address',
                ]
            )
            ->add(
                'city',
                ModelFilter::class,
                [
                    'label' => 'backend.admin.parent.city',
                    'field_type' => ModelAutocompleteType::class,
                    'field_options' => [
                        'class' => City::class,
                        'property' => ['name', 'postalCode'],
                    ],
                ]
            )
            ->add(
                'payment',
                null,
                [
                    'label' => 'backend.admin.parent.payment',
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'choices' => StudentPaymentEnum::getEnumArray(),
                        'expanded' => false,
                        'multiple' => false,
                    ],
                ]
            )
            ->add(
                'bank.name',
                null,
                [
                    'label' => 'backend.admin.bank.name',
                ]
            )
            ->add(
                'bank.swiftCode',
                null,
                [
                    'label' => 'backend.admin.bank.swiftCode',
                ]
            )
            ->add(
                'bank.accountNumber',
                null,
                [
                    'label' => 'IBAN',
                ]
            )
            ->add(
                'bankCreditorSepa',
                null,
                [
                    'label' => 'backend.admin.bank.creditor_bank_name',
                    'field_type' => EntityType::class,
                    'field_options' => [
                        'required' => false,
                        'class' => BankCreditorSepa::class,
                        'query_builder' => $this->em->getRepository(BankCreditorSepa::class)->getAllSortedByNameQB(),
                    ],
                ]
            )
            ->add(
                'dischargeDate',
                DateFilter::class,
                [
                    'label' => 'backend.admin.student.dischargeDate',
                    'field_type' => DatePickerType::class,
                    'field_options' => [
                        'widget' => 'single_text',
                        'format' => 'dd-MM-yyyy',
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
                    'label' => 'backend.admin.parent.name',
                    'editable' => true,
                ]
            )
            ->add(
                'surname',
                null,
                [
                    'label' => 'backend.admin.parent.surname',
                    'editable' => true,
                ]
            )
        ;
        if ($this->isAdminUser()) {
            $list
                ->add(
                    'phone',
                    null,
                    [
                        'label' => 'backend.admin.parent.phone',
                        'editable' => true,
                    ]
                )
                ->add(
                    'email',
                    null,
                    [
                        'label' => 'backend.admin.parent.email',
                        'editable' => true,
                    ]
                )
            ;
        }
        $list
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
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'edit' => ['template' => 'Admin/Buttons/list__action_edit_button.html.twig'],
                    ],
                ]
            )
        ;
    }

    public function configureExportFields(): array
    {
        $result = [
            'dni',
            'name',
            'surname',
            'phone',
            'email',
            'address',
            'city',
            'paymentString',
            'bank.name',
            'bank.swiftCode',
            'bank.accountNumber',
            'bankCreditorSepa.name',
            'bankCreditorSepa.iban',
            'dischargeDateString',
            'enabled',
        ];
        if (!$this->isAdminUser()) {
            unset($result[3], $result[4]);
        }

        return $result;
    }

    /**
     * @param Person $object
     */
    public function prePersist($object): void
    {
        $this->commonPreActions($object);
    }

    /**
     * @param Person $object
     */
    public function preUpdate($object): void
    {
        $this->commonPreActions($object);
    }

    private function commonPreActions($object): void
    {
        if ($object->getBank() && $object->getBank()->getAccountNumber()) {
            $object->getBank()->setAccountNumber(strtoupper($object->getBank()->getAccountNumber()));
        }
        if ($object->getBank() && $object->getBank()->getSwiftCode()) {
            $object->getBank()->setSwiftCode(strtoupper($object->getBank()->getSwiftCode()));
        }
    }
}
