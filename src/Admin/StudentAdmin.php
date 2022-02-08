<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\BankCreditorSepa;
use App\Entity\City;
use App\Entity\ClassGroup;
use App\Entity\Invoice;
use App\Entity\Person;
use App\Entity\Receipt;
use App\Entity\Student;
use App\Entity\Tariff;
use App\Enum\SchoolYearChoicesGeneratorEnum;
use App\Enum\StudentAgesEnum;
use App\Enum\StudentPaymentEnum;
use DateTime;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\DoctrineORMAdminBundle\Filter\CallbackFilter;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\DoctrineORMAdminBundle\Filter\ModelFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class StudentAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Student';
    protected $baseRoutePattern = 'students/student';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::ASC;
        $sortValues[DatagridInterface::SORT_BY] = 'surname';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('imagerights', $this->getRouterIdParameter().'/image-rights')
            ->add('sepaagreement', $this->getRouterIdParameter().'/sepa-agreement')
            ->remove('batch')
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray('backend.admin.general', 3))
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.student.name',
                ]
            )
            ->add(
                'surname',
                null,
                [
                    'label' => 'backend.admin.student.surname',
                ]
            )
            ->add(
                'parent',
                EntityType::class,
                [
                    'label' => 'backend.admin.student.parent',
                    'required' => false,
                    'class' => Person::class,
                    'choice_label' => 'fullcanonicalname',
                    'query_builder' => $this->em->getRepository(Person::class)->getEnabledSortedBySurnameQB(),
                ]
            )
            ->add(
                'comments',
                TextareaType::class,
                [
                    'label' => 'backend.admin.student.comments',
                    'required' => false,
                    'attr' => [
                        'rows' => 8,
                        'style' => 'resize:vertical',
                    ],
                ]
            )
            ->end()
            ->with('backend.admin.contact.contact', $this->getFormMdSuccessBoxArray('backend.admin.contact.contact', 3))
            ->add(
                'phone',
                null,
                [
                    'label' => 'backend.admin.student.phone',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.student.email',
                    'required' => false,
                ]
            )
            ->add(
                'address',
                null,
                [
                    'label' => 'backend.admin.student.address',
                    'required' => false,
                ]
            )
            ->add(
                'city',
                EntityType::class,
                [
                    'label' => 'backend.admin.student.city',
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
                    'help' => 'backend.admin.student.payment_no_parent_help',
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
                    'query_builder' => $this->em->getRepository(BankCreditorSepa::class)->getEnabledSortedByNameQB(),
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls', 3))
            ->add(
                'dni',
                null,
                [
                    'label' => 'backend.admin.student.dni',
                    'required' => false,
                ]
            )
            ->add(
                'birthDate',
                DatePickerType::class,
                [
                    'label' => 'backend.admin.student.birthDate',
                    'format' => 'd/M/y',
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
                'schedule',
                null,
                [
                    'label' => 'backend.admin.student.schedule',
                ]
            )
            ->add(
                'tariff',
                EntityType::class,
                [
                    'label' => 'backend.admin.student.tariff',
                    'required' => true,
                    'class' => Tariff::class,
                    'query_builder' => $this->em->getRepository(Tariff::class)->findAllSortedByYearAndPriceQB(),
                ]
            )
            ->add(
                'hasImageRightsAccepted',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.imagerigths.checkbox_label',
                    'required' => false,
                ]
            )
            ->add(
                'hasSepaAgreementAccepted',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.sepaagreement.checkbox_label',
                    'required' => false,
                ]
            )
            ->add(
                'isPaymentExempt',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.student.is_payment_excempt',
                    'required' => false,
                ]
            )
            ->add(
                'hasAcceptedInternalRegulations',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.internalregulations.checkbox_label',
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
                    'label' => 'backend.admin.student.dni',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.student.name',
                ]
            )
            ->add(
                'surname',
                null,
                [
                    'label' => 'backend.admin.student.surname',
                ]
            )
            ->add(
                'parent',
                ModelFilter::class,
                [
                    'label' => 'backend.admin.student.parent',
                    'field_type' => ModelAutocompleteType::class,
                    'field_options' => [
                        'class' => Person::class,
                        'property' => ['name', 'surname'],
                    ],
                ]
            )
            ->add(
                'comments',
                null,
                [
                    'label' => 'backend.admin.student.comments',
                ]
            )
            ->add(
                'phone',
                null,
                [
                    'label' => 'backend.admin.student.phone',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.student.email',
                ]
            )
            ->add(
                'address',
                null,
                [
                    'label' => 'backend.admin.student.address',
                ]
            )
            ->add(
                'city',
                null,
                [
                    'label' => 'backend.admin.student.city',
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
                'bankCreditorSepa',
                null,
                [
                    'label' => 'backend.admin.bank.creditor_bank_name',
                    'field_type' => EntityType::class,
                    'field_options' => [
                        'class' => BankCreditorSepa::class,
                        'query_builder' => $this->em->getRepository(BankCreditorSepa::class)->getAllSortedByNameQB(),
                    ],
                ]
            )
            ->add(
                'events.group',
                null,
                [
                    'label' => 'backend.admin.event.group',
                    'field_type' => EntityType::class,
                    'field_options' => [
                        'class' => ClassGroup::class,
                        'query_builder' => $this->em->getRepository(ClassGroup::class)->getEnabledSortedByCodeQB(),
                    ],
                ]
            )
            ->add(
                'schoolYear',
                CallbackFilter::class,
                [
                    'label' => 'backend.admin.class_group.school_year',
                    'callback' => [$this, 'buildDatagridSchoolYearFilter'],
                    'required' => true,
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'choices' => SchoolYearChoicesGeneratorEnum::getSchoolYearChoicesArray(),
                        'expanded' => false,
                        'multiple' => false,
                    ],
                ]
            )
            ->add(
                'age',
                CallbackFilter::class,
                [
                    'label' => 'backend.admin.student.age',
                    'callback' => [$this, 'buildDatagridAgesFilter'],
                    'required' => true,
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'choices' => StudentAgesEnum::getReversedEnumTranslatedArray(),
                        'expanded' => false,
                        'multiple' => false,
                    ],
                ]
            )
            ->add(
                'birthDate',
                DateFilter::class,
                [
                    'label' => 'backend.admin.student.birthDate',
                    'field_type' => DatePickerType::class,
                    'field_options' => [
                        'widget' => 'single_text',
                        'format' => 'dd-MM-yyyy',
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
                'schedule',
                null,
                [
                    'label' => 'backend.admin.student.schedule',
                ]
            )
            ->add(
                'tariff',
                null,
                [
                    'label' => 'backend.admin.student.tariff',
                ]
            )
            ->add(
                'hasImageRightsAccepted',
                null,
                [
                    'label' => 'backend.admin.imagerigths.checkbox_label',
                ]
            )
            ->add(
                'hasSepaAgreementAccepted',
                null,
                [
                    'label' => 'backend.admin.sepaagreement.checkbox_label',
                ]
            )
            ->add(
                'isPaymentExempt',
                null,
                [
                    'label' => 'backend.admin.student.is_payment_excempt',
                ]
            )
            ->add(
                'hasAcceptedInternalRegulations',
                null,
                [
                    'label' => 'backend.admin.internalregulations.checkbox_label',
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

    public function buildDatagridSchoolYearFilter($queryBuilder, $alias, $field, $value): bool
    {
        if ($value && array_key_exists('value', $value)) {
            $schoolYear = (int) $value['value'];
            $begin = new DateTime();
            $begin->setDate($schoolYear, 8, 31);
            $begin->setTime(0, 0);
            $end = new DateTime();
            $end->setDate($schoolYear + 1, 9, 1);
            $end->setTime(0, 0);
            $queryBuilder->join($alias.'.events', 'e');
            $queryBuilder->andWhere('e.begin > :begin');
            $queryBuilder->andWhere('e.begin < :end');
            $queryBuilder->setParameter('begin', $begin);
            $queryBuilder->setParameter('end', $end);

            return true;
        }

        return false;
    }

    public function buildDatagridAgesFilter($queryBuilder, $alias, $field, $value): bool
    {
        if ($value && array_key_exists('value', $value)) {
            $age = (int) $value['value'];
            if ($age < StudentAgesEnum::AGE_20_plus) {
                $queryBuilder->andWhere('TIMESTAMPDIFF(year, '.$alias.'.birthDate, NOW()) = :age');
            } else {
                $queryBuilder->andWhere('TIMESTAMPDIFF(year, '.$alias.'.birthDate, NOW()) >= :age');
            }
            $queryBuilder->setParameter('age', $age);

            return true;
        }

        return false;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.student.name',
                    'editable' => true,
                ]
            )
            ->add(
                'surname',
                null,
                [
                    'label' => 'backend.admin.student.surname',
                    'editable' => true,
                ]
            )
            ->add(
                'phone',
                null,
                [
                    'label' => 'backend.admin.student.phone',
                    'editable' => true,
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.student.email',
                    'editable' => true,
                ]
            )
            ->add(
                'hasImageRightsAccepted',
                null,
                [
                    'label' => 'backend.admin.imagerigths.checkbox_label',
                    'editable' => true,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'hasSepaAgreementAccepted',
                null,
                [
                    'label' => 'backend.admin.sepaagreement.checkbox_label',
                    'editable' => true,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'hasAcceptedInternalRegulations',
                null,
                [
                    'label' => 'backend.admin.internalregulations.checkbox_label',
                    'editable' => true,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
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
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'edit' => [
                            'template' => 'Admin/Buttons/list__action_edit_button.html.twig',
                        ],
                        'show' => [
                            'template' => 'Admin/Buttons/list__action_show_button.html.twig',
                        ],
                        'imagerights' => [
                            'template' => 'Admin/Cells/list__action_image_rights.html.twig',
                        ],
                        'sepaagreement' => [
                            'template' => 'Admin/Cells/list__action_sepa_agreement.html.twig',
                        ],
                        'delete' => [
                            'template' => 'Admin/Buttons/list__action_delete_student_button.html.twig',
                        ],
                    ],
                ]
            )
        ;
    }

    public function configureExportFields(): array
    {
        return [
            'dni',
            'name',
            'surname',
            'parent.fullCanonicalName',
            'comments',
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
            'birthDateString',
            'dischargeDateString',
            'schedule',
            'tariff',
            'hasImageRightsAccepted',
            'hasSepaAgreementAccepted',
            'hasAcceptedInternalRegulations',
            'enabled',
        ];
    }

    public function preRemove($object): void
    {
        $relatedReceipts = $this->getModelManager()->findBy(Receipt::class, [
            'student' => $object,
        ]);
        /** @var Receipt $relatedReceipt */
        foreach ($relatedReceipts as $relatedReceipt) {
            $this->getModelManager()->delete($relatedReceipt);
        }
        $relatedInvoices = $this->getModelManager()->findBy(Invoice::class, [
            'student' => $object,
        ]);
        /** @var Invoice $relatedInvoice */
        foreach ($relatedInvoices as $relatedInvoice) {
            $this->getModelManager()->delete($relatedInvoice);
        }
    }

    /**
     * @param Student $object
     */
    public function prePersist($object): void
    {
        $this->commonPreActions($object);
    }

    /**
     * @param Student $object
     */
    public function preUpdate($object): void
    {
        $this->commonPreActions($object);
    }

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
