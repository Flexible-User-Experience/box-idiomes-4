<?php

namespace App\Admin;

use App\Entity\Invoice;
use App\Entity\Person;
use App\Entity\Receipt;
use App\Entity\Student;
use App\Enum\InvoiceYearMonthEnum;
use App\Enum\StudentPaymentEnum;
use DateTime;
use Exception;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\DoctrineORMAdminBundle\Filter\ModelAutocompleteFilter;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class InvoiceAdmin.
 *
 * @category Admin
 */
class InvoiceAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Invoice';
    protected $baseRoutePattern = 'billings/invoice';
    protected $maxPerPage = 400;
    protected $datagridValues = [
        '_sort_by' => 'id',
        '_sort_order' => 'desc',
        '_per_page' => 400,
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        $collection
            ->add('pdf', $this->getRouterIdParameter().'/pdf')
            ->add('send', $this->getRouterIdParameter().'/send')
            ->add('generateDirectDebit', $this->getRouterIdParameter().'/generate-direct-debit-xml')
            ->remove('show')
        ;
    }

    /**
     * @param array $actions
     */
    public function configureBatchActions($actions): array
    {
        if ($this->hasRoute('edit') && $this->hasAccess('edit')) {
            $actions['generatesepaxmls'] = [
                'label' => 'backend.admin.invoice.batch_action',
                'translation_domain' => 'messages',
                'ask_confirmation' => false,
            ];
        }

        return $actions;
    }

    /**
     * Get the list of actions that can be accessed directly from the dashboard.
     */
    public function getDashboardActions(): array
    {
        $actions = parent::getDashboardActions();
        $actions['generate'] = [
            'label' => 'backend.admin.invoice.generate_batch',
            'translation_domain' => 'messages',
            'url' => $this->generateUrl('generate'),
            'icon' => 'inbox',
        ];

        return $actions;
    }

    /**
     * @throws Exception
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $now = new DateTime();
        $currentYear = $now->format('Y');

        $formMapper
            ->with('backend.admin.invoice.invoice', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'date',
                DatePickerType::class,
                [
                    'label' => 'backend.admin.receipt.date',
                    'format' => 'd/M/y',
                    'required' => $this->id($this->getSubject()) ? false : true,
                    'disabled' => $this->id($this->getSubject()) ? true : false,
                ]
            )
            ->add(
                'year',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.invoice.year',
                    'required' => true,
                    'choices' => InvoiceYearMonthEnum::getYearEnumArray(),
                    'preferred_choices' => $currentYear,
                ]
            )
            ->add(
                'month',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.invoice.month',
                    'required' => true,
                    'choices' => InvoiceYearMonthEnum::getMonthEnumArray(),
                ]
            )
            ->add(
                'student',
                EntityType::class,
                [
                    'label' => 'backend.admin.invoice.student',
                    'required' => true,
                    'class' => Student::class,
                    'choice_label' => 'fullCanonicalName',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.student_repository')->getEnabledSortedBySurnameValidTariffQB(),
                ]
            )
            ->add(
                'person',
                EntityType::class,
                [
                    'label' => 'backend.admin.invoice.person',
                    'required' => false,
                    'class' => Person::class,
                    'choice_label' => 'fullCanonicalName',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.parent_repository')->getEnabledSortedBySurnameQB(),
                    'disabled' => true,
                ]
            )
            ->end()
            ->with('backend.admin.invoice.detail', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'taxPercentage',
                null,
                [
                    'label' => 'backend.admin.invoice.taxPercentage',
                    'required' => false,
                    'help' => '%',
                ]
            )
            ->add(
                'irpfPercentage',
                null,
                [
                    'label' => 'backend.admin.invoice.irpfPercentage',
                    'required' => false,
                    'help' => '%',
                ]
            )
            ->add(
                'baseAmount',
                null,
                [
                    'label' => 'backend.admin.invoice.baseAmount',
                    'required' => false,
                    'disabled' => true,
                ]
            )
            ->add(
                'totalAmount',
                null,
                [
                    'label' => 'backend.admin.invoice.totalAmount',
                    'required' => false,
                    'disabled' => true,
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'receipt',
                EntityType::class,
                [
                    'label' => 'backend.admin.invoice.receipt',
                    'required' => false,
                    'class' => Receipt::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.receipt_repository')->getAllSortedByNumberDescQB(),
                ]
            )
            ->add(
                'discountApplied',
                null,
                [
                    'label' => 'backend.admin.invoice.discountApplied',
                    'required' => false,
                    'disabled' => true,
                ]
            )
            ->add(
                'isForPrivateLessons',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.is_for_private_lessons',
                    'required' => false,
                    'disabled' => false,
                ]
            )
            ->add(
                'isSepaXmlGenerated',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.receipt.isSepaXmlGenerated',
                    'required' => false,
                    'disabled' => true,
                ]
            )
            ->add(
                'sepaXmlGeneratedDate',
                DatePickerType::class,
                [
                    'label' => 'backend.admin.receipt.sepaXmlGeneratedDate',
                    'format' => 'd/M/y',
                    'required' => false,
                    'disabled' => true,
                ]
            )
            ->add(
                'isSended',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.invoice.isSended',
                    'required' => false,
                    'disabled' => true,
                ]
            )
            ->add(
                'sendDate',
                DatePickerType::class,
                [
                    'label' => 'backend.admin.invoice.sendDate',
                    'format' => 'd/M/y',
                    'required' => false,
                    'disabled' => true,
                ]
            )
            ->add(
                'isPayed',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.invoice.isPayed',
                    'required' => false,
                ]
            )
            ->add(
                'paymentDate',
                DatePickerType::class,
                [
                    'label' => 'backend.admin.invoice.paymentDate',
                    'format' => 'd/M/y',
                    'required' => false,
                ]
            )
            ->end();
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjetcs
            $formMapper
                ->with('backend.admin.invoice.lines', $this->getFormMdSuccessBoxArray(12))
                ->add(
                    'lines',
                    CollectionType::class,
                    [
                        'label' => 'backend.admin.invoice.line',
                        'required' => true,
                        'error_bubbling' => true,
                        'by_reference' => false,
                    ],
                    [
                        'edit' => 'inline',
                        'inline' => 'table',
                    ]
                )
                ->end()
            ;
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add(
                'id',
                null,
                [
                    'label' => 'backend.admin.invoice.id',
                ]
            )
            ->add(
                'date',
                DateFilter::class,
                [
                    'label' => 'backend.admin.receipt.date',
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
                'year',
                null,
                [
                    'label' => 'backend.admin.invoice.year',
                ]
            )
            ->add(
                'month',
                null,
                [
                    'label' => 'backend.admin.invoice.month',
                ],
                ChoiceType::class,
                [
                    'choices' => InvoiceYearMonthEnum::getMonthEnumArray(),
                    'expanded' => false,
                    'multiple' => false,
                ]
            )
            ->add(
                'receipt',
                ModelAutocompleteFilter::class,
                [
                    'label' => 'backend.admin.invoice.receipt',
                ],
                null,
                [
                    'class' => Receipt::class,
                    'property' => ['month', 'year'],
                ]
            )
            ->add(
                'student',
                ModelAutocompleteFilter::class,
                [
                    'label' => 'backend.admin.invoice.student',
                ],
                null,
                [
                    'class' => Student::class,
                    'property' => ['name', 'surname'],
                ]
            )
            ->add(
                'person',
                ModelAutocompleteFilter::class,
                [
                    'label' => 'backend.admin.invoice.person',
                ],
                null,
                [
                    'class' => Person::class,
                    'property' => ['name', 'surname'],
                ]
            )
            ->add(
                'student.payment',
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
                'discountApplied',
                null,
                [
                    'label' => 'backend.admin.invoice.discountApplied',
                ]
            )
            ->add(
                'baseAmount',
                null,
                [
                    'label' => 'backend.admin.invoice.baseAmount',
                ]
            )
            ->add(
                'taxPercentage',
                null,
                [
                    'label' => 'backend.admin.invoice.taxPercentage',
                ]
            )
            ->add(
                'irpfPercentage',
                null,
                [
                    'label' => 'backend.admin.invoice.irpfPercentage',
                ]
            )
            ->add(
                'totalAmount',
                null,
                [
                    'label' => 'backend.admin.invoice.totalAmount',
                ]
            )
            ->add(
                'isForPrivateLessons',
                null,
                [
                    'label' => 'backend.admin.is_for_private_lessons',
                ]
            )
            ->add(
                'isSepaXmlGenerated',
                null,
                [
                    'label' => 'backend.admin.receipt.isSepaXmlGenerated',
                ]
            )
            ->add(
                'sepaXmlGeneratedDate',
                DateFilter::class,
                [
                    'label' => 'backend.admin.receipt.sepaXmlGeneratedDate',
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
                'isSended',
                null,
                [
                    'label' => 'backend.admin.invoice.isSended',
                ]
            )
            ->add(
                'sendDate',
                DateFilter::class,
                [
                    'label' => 'backend.admin.invoice.sendDate',
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
                'isPayed',
                null,
                [
                    'label' => 'backend.admin.invoice.isPayed',
                ]
            )
            ->add(
                'paymentDate',
                DateFilter::class,
                [
                    'label' => 'backend.admin.invoice.paymentDate',
                    'field_type' => DatePickerType::class,
                    'format' => 'd-m-Y',
                ],
                null,
                [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                ]
            )
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add(
                'id',
                null,
                [
                    'label' => 'backend.admin.invoice.id',
                    'template' => 'Admin/Cells/list__cell_invoice_number.html.twig',
                ]
            )
            ->add(
                'date',
                null,
                [
                    'label' => 'backend.admin.receipt.date',
                    'template' => 'Admin/Cells/list__cell_receipt_date.html.twig',
                    'editable' => false,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'year',
                null,
                [
                    'label' => 'backend.admin.invoice.year',
                    'template' => 'Admin/Cells/list__cell_event_year.html.twig',
                    'editable' => false,
                ]
            )
            ->add(
                'month',
                null,
                [
                    'label' => 'backend.admin.invoice.month',
                    'template' => 'Admin/Cells/list__cell_event_month.html.twig',
                ]
            )
            ->add(
                'receipt',
                null,
                [
                    'label' => 'backend.admin.invoice.receipt',
                    'editable' => false,
                    'associated_property' => 'receiptNumber',
                    'sortable' => true,
                    'sort_field_mapping' => ['fieldName' => 'id'],
                    'sort_parent_association_mappings' => [['fieldName' => 'receipt']],
                ]
            )
            ->add(
                'student',
                null,
                [
                    'label' => 'backend.admin.invoice.student',
                    'editable' => false,
                    'associated_property' => 'fullCanonicalName',
                    'sortable' => true,
                    'sort_field_mapping' => ['fieldName' => 'surname'],
                    'sort_parent_association_mappings' => [['fieldName' => 'student']],
                ]
            )
            ->add(
                'baseAmount',
                null,
                [
                    'label' => 'backend.admin.invoice.baseAmount',
                    'template' => 'Admin/Cells/list__cell_invoice_base_amount.html.twig',
                    'editable' => false,
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                ]
            )
            ->add(
                'totalAmount',
                null,
                [
                    'label' => 'backend.admin.invoice.totalAmount',
                    'template' => 'Admin/Cells/list__cell_invoice_total_amount.html.twig',
                    'editable' => false,
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                ]
            )
            ->add(
                'isSepaXmlGenerated',
                null,
                [
                    'label' => 'backend.admin.receipt.isSepaXmlGenerated',
                    'editable' => false,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'isSended',
                null,
                [
                    'label' => 'backend.admin.invoice.isSended',
                    'editable' => false,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'isPayed',
                null,
                [
                    'label' => 'backend.admin.invoice.isPayed',
                    'editable' => false,
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
                        'invoice' => ['template' => 'Admin/Buttons/list__action_invoice_pdf_button.html.twig'],
                        'send' => ['template' => 'Admin/Buttons/list__action_invoice_send_button.html.twig'],
                        'generateDirectDebit' => ['template' => 'Admin/Buttons/list__action_generate_direct_debit_xml_button.html.twig'],
                        'delete' => ['template' => 'Admin/Buttons/list__action_delete_superadmin_button.html.twig'],
                    ],
                    'label' => 'backend.admin.actions',
                ]
            );
    }

    public function getExportFields(): array
    {
        return [
            'invoiceNumber',
            'dateString',
            'year',
            'month',
            'receipt.receiptNumber',
            'student.fullCanonicalName',
            'person.fullCanonicalName',
            'student.paymentString',
            'discountApplied',
            'baseAmountString',
            'taxPercentage',
            'irpfPercentage',
            'totalAmountString',
            'isForPrivateLessons',
            'isSepaXmlGenerated',
            'sepaXmlGeneratedDateString',
            'isSended',
            'sendDateString',
            'isPayed',
            'paymentDateString',
        ];
    }

    /**
     * @param Invoice $object
     */
    public function prePersist($object): void
    {
        $this->commonPreActions($object);
    }

    /**
     * @param Invoice $object
     */
    public function preUpdate($object): void
    {
        $this->commonPreActions($object);
    }

    /**
     * @param Invoice $object
     */
    private function commonPreActions($object): void
    {
        if ($object->getStudent()->getParent()) {
            $object->setPerson($object->getStudent()->getParent());
        }
        $object
            ->setBaseAmount($object->calculateBaseAmount())
            ->setTotalAmount($object->calculateTotalAmount())
        ;
    }
}
