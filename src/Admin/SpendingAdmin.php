<?php

namespace App\Admin;

use App\Entity\Provider;
use App\Entity\SpendingCategory;
use App\Enum\StudentPaymentEnum;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Class SpendingAdmin.
 *
 * @category Admin
 */
class SpendingAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Spending';
    protected $baseRoutePattern = 'purchases/spending';
    protected $datagridValues = [
        '_sort_by' => 'date',
        '_sort_order' => 'desc',
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        parent::configureRoutes($collection);
        $collection->add('duplicate', $this->getRouterIdParameter().'/duplicate');
    }

    /**
     * @throws \Twig\Error\Error
     */
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(5))
            ->add(
                'date',
                DatePickerType::class,
                [
                    'label' => 'backend.admin.spending.date',
                    'format' => 'd/M/y',
                    'required' => true,
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'label' => 'backend.admin.spending.category',
                    'required' => false,
                    'class' => SpendingCategory::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.spending_category_repository')->getEnabledSortedByNameQB(),
                ]
            )
            ->add(
                'provider',
                EntityType::class,
                [
                    'label' => 'backend.admin.spending.provider',
                    'required' => false,
                    'class' => Provider::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.provider_repository')->getEnabledSortedByNameQB(),
                ]
            )
            ->add(
                'description',
                null,
                [
                    'label' => 'backend.admin.spending.description',
                    'required' => true,
                ]
            )
            ->end()
            ->with('backend.admin.documents', $this->getFormMdSuccessBoxArray(4))
            ->add(
                'documentFile',
                FileType::class,
                [
                    'label' => 'backend.admin.spending.document',
                    'help' => $this->getSmartHelper('getDocument', 'documentFile'),
                    'required' => false,
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'baseAmount',
                null,
                [
                    'label' => 'backend.admin.invoice.baseAmount',
                    'required' => true,
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
            ->add(
                'paymentMethod',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.customer.payment_method',
                    'choices' => StudentPaymentEnum::getEnumArrayWithCreditCard(),
                    'required' => true,
                ]
            )
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add(
                'date',
                DateFilter::class,
                [
                    'label' => 'backend.admin.spending.date',
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
                'category',
                null,
                [
                    'label' => 'backend.admin.spending.category',
                ],
                EntityType::class,
                [
                    'expanded' => false,
                    'multiple' => false,
                    'class' => SpendingCategory::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.spending_category_repository')->getEnabledSortedByNameQB(),
                ]
            )
            ->add(
                'provider',
                null,
                [
                    'label' => 'backend.admin.spending.provider',
                ],
                EntityType::class,
                [
                    'expanded' => false,
                    'multiple' => false,
                    'class' => Provider::class,
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.provider_repository')->getEnabledSortedByNameQB(),
                ]
            )
            ->add(
                'description',
                null,
                [
                    'label' => 'backend.admin.spending.description',
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
            ->add(
                'paymentMethod',
                null,
                [
                    'label' => 'backend.admin.customer.payment_method',
                ],
                ChoiceType::class,
                [
                    'label' => 'backend.admin.customer.payment_method',
                    'choices' => StudentPaymentEnum::getEnumArray(),
                    'required' => true,
                ]
            )
        ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add(
                'date',
                null,
                [
                    'label' => 'backend.admin.spending.date',
                    'format' => 'd/m/Y',
                    'editable' => true,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'category',
                null,
                [
                    'label' => 'backend.admin.spending.category',
                    'editable' => false,
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => ['fieldName' => 'name'],
                    'sort_parent_association_mappings' => [['fieldName' => 'category']],
                ]
            )
            ->add(
                'provider',
                null,
                [
                    'label' => 'backend.admin.spending.provider',
                    'editable' => false,
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => ['fieldName' => 'name'],
                    'sort_parent_association_mappings' => [['fieldName' => 'category']],
                ]
            )
            ->add(
                'description',
                null,
                [
                    'label' => 'backend.admin.spending.description',
                    'editable' => true,
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
                    'label' => 'backend.admin.actions',
                    'actions' => [
                        'edit' => ['template' => 'Admin/Buttons/list__action_edit_button.html.twig'],
                        'document' => ['template' => 'Admin/Buttons/list__action_spending_document_button.html.twig'],
                        'duplicate' => ['template' => 'Admin/Buttons/list__action_invoice_duplicate_button.html.twig'],
                        'delete' => ['template' => 'Admin/Buttons/list__action_delete_button.html.twig'],
                    ],
                ]
            )
        ;
    }

    public function getExportFields(): array
    {
        return [
            'dateString',
            'category',
            'provider',
            'description',
            'baseAmountString',
            'isPayed',
            'paymentDateString',
            'paymentString',
            'document',
        ];
    }
}
