<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\Provider;
use App\Entity\SpendingCategory;
use App\Enum\StudentPaymentEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichFileType;

final class SpendingAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Spending';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::DESC;
        $sortValues[DatagridInterface::SORT_BY] = 'date';
    }

    public function generateBaseRoutePattern(bool $isChildAdmin = false): string
    {
        return 'purchases/spending';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection->add('duplicate', $this->getRouterIdParameter().'/duplicate');
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray('backend.admin.general', 5))
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
                    'query_builder' => $this->em->getRepository(SpendingCategory::class)->getEnabledSortedByNameQB(),
                ]
            )
            ->add(
                'provider',
                EntityType::class,
                [
                    'label' => 'backend.admin.spending.provider',
                    'required' => false,
                    'class' => Provider::class,
                    'query_builder' => $this->em->getRepository(Provider::class)->getEnabledSortedByNameQB(),
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
            ->with('backend.admin.documents', $this->getFormMdSuccessBoxArray('backend.admin.documents', 4))
            ->add(
                'documentFile',
                VichFileType::class,
                [
                    'label' => 'backend.admin.spending.document',
                    'download_uri' => !$this->isFormToCreateNewRecord() ? $this->getRouteGenerator()->generate('media_inline_spending_item', ['id' => $this->id($this->getSubject())]) : false,
                    'allow_delete' => true,
                    'asset_helper' => true,
                    'required' => false,
                    'help' => 'Pots adjuntar qualsevol document amb format PDF de fins a 10MB',
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls', 3))
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

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'date',
                DateRangeFilter::class,
                [
                    'label' => 'backend.admin.spending.date',
                    'field_options_start' => [
                        'widget' => 'single_text',
                        'format' => 'dd-MM-yyyy',
                    ],
                    'field_options_end' => [
                        'widget' => 'single_text',
                        'format' => 'dd-MM-yyyy',
                    ],
                ]
            )
            ->add(
                'category',
                null,
                [
                    'label' => 'backend.admin.spending.category',
                    'field_type' => EntityType::class,
                    'field_options' => [
                        'expanded' => false,
                        'multiple' => false,
                        'class' => SpendingCategory::class,
                        'query_builder' => $this->em->getRepository(SpendingCategory::class)->getEnabledSortedByNameQB(),
                    ],
                ]
            )
            ->add(
                'provider',
                null,
                [
                    'label' => 'backend.admin.spending.provider',
                    'field_type' => EntityType::class,
                    'field_options' => [
                        'expanded' => false,
                        'multiple' => false,
                        'class' => Provider::class,
                        'query_builder' => $this->em->getRepository(Provider::class)->getEnabledSortedByNameQB(),
                    ],
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
                null,
                [
                    'label' => 'backend.admin.invoice.paymentDate',
                    'field_type' => DatePickerType::class,
                    'field_options' => [
                        'widget' => 'single_text',
                        'format' => 'dd-MM-yyyy',
                    ],
                ]
            )
            ->add(
                'paymentMethod',
                null,
                [
                    'label' => 'backend.admin.customer.payment_method',
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'label' => 'backend.admin.customer.payment_method',
                        'choices' => StudentPaymentEnum::getEnumArray(),
                        'required' => true,
                    ],
                ]
            )
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
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
                ListMapper::NAME_ACTIONS,
                null,
                [
                    'label' => 'backend.admin.actions',
                    'header_style' => 'width:152px',
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'edit' => [
                            'template' => 'Admin/Buttons/list__action_edit_button.html.twig',
                        ],
                        'document' => [
                            'template' => 'Admin/Buttons/list__action_spending_document_button.html.twig',
                        ],
                        'duplicate' => [
                            'template' => 'Admin/Buttons/list__action_invoice_duplicate_button.html.twig',
                        ],
                        'delete' => [
                            'template' => 'Admin/Buttons/list__action_delete_button.html.twig',
                        ],
                    ],
                ]
            )
        ;
    }

    public function configureExportFields(): array
    {
        return [
            'dateString',
            'category',
            'provider.tic',
            'provider.name',
            'provider.alias',
            'description',
            'amountString',
            'isPayed',
            'paymentDateString',
            'paymentMethodString',
            'document',
        ];
    }
}
