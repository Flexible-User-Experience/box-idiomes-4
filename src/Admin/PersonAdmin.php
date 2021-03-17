<?php

namespace App\Admin;

use App\Entity\BankCreditorSepa;
use App\Entity\City;
use App\Entity\Person;
use App\Enum\StudentPaymentEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\DoctrineORMAdminBundle\Filter\ModelAutocompleteFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class PersonAdmin.
 *
 * @category Admin
 */
class PersonAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Person';
    protected $baseRoutePattern = 'students/parent';
    protected $datagridValues = [
        '_sort_by' => 'surname',
        '_sort_order' => 'asc',
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        parent::configureRoutes($collection);
        $collection->remove('delete');
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(3))
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
            ->with('backend.admin.contact.contact', $this->getFormMdSuccessBoxArray(3))
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
                    'required' => false,
                ]
            )
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
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.city_repository')->getEnabledSortedByNameQB(),
                ]
            )
            ->end()
            ->with('backend.admin.student.payment_information', $this->getFormMdSuccessBoxArray(3))
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
                'bank',
                AdminType::class,
                [
                    'label' => ' ',
                    'required' => false,
                    'btn_add' => false,
                    'by_reference' => false,
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
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.bank_creditor_sepa_repository')->getEnabledSortedByNameQB(),
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
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

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
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
            ->add(
                'address',
                null,
                [
                    'label' => 'backend.admin.parent.address',
                ]
            )
            ->add(
                'city',
                ModelAutocompleteFilter::class,
                [
                    'label' => 'backend.admin.parent.city',
                ],
                null,
                [
                    'class' => City::class,
                    'property' => ['name', 'postalCode'],
                ]
            )
            ->add(
                'payment',
                null,
                [
                    'label' => 'backend.admin.parent.payment',
                ],
                ChoiceType::class,
                [
                    'choices' => StudentPaymentEnum::getEnumArray(),
                    'expanded' => false,
                    'multiple' => false,
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
                ],
                EntityType::class,
                [
                    'required' => false,
                    'class' => BankCreditorSepa::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.bank_creditor_sepa_repository')->getAllSortedByNameQB(),
                ]
            )
            ->add(
                'dischargeDate',
                DateFilter::class,
                [
                    'label' => 'backend.admin.student.dischargeDate',
                    'field_type' => DatePickerType::class,
                    'format' => 'd-m-Y',
                ],
                null,
                [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
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
                '_action',
                'actions',
                [
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'edit' => ['template' => 'Admin/Buttons/list__action_edit_button.html.twig'],
                    ],
                    'label' => 'Accions',
                ]
            )
        ;
    }

    public function getExportFields(): array
    {
        return [
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

    /**
     * @param Person $object
     */
    private function commonPreActions($object): void
    {
        if ($object->getBank()->getAccountNumber()) {
            $object->getBank()->setAccountNumber(strtoupper($object->getBank()->getAccountNumber()));
        }
        if ($object->getBank()->getSwiftCode()) {
            $object->getBank()->setSwiftCode(strtoupper($object->getBank()->getSwiftCode()));
        }
    }
}
