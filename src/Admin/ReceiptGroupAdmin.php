<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\AbstractBase;
use App\Entity\BankCreditorSepa;
use App\Entity\TrainingCenter;
use App\Enum\InvoiceYearMonthEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class ReceiptGroupAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'ReceiptGroup';
    protected $baseRoutePattern = 'billings/receipt-group';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::PER_PAGE] = 25;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::DESC;
        $sortValues[DatagridInterface::SORT_BY] = 'createdAt';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection
            ->remove('create')
            ->remove('edit')
            ->remove('export')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'createdAt',
                DateFilter::class,
                [
                    'label' => 'backend.admin.date',
                    'field_type' => DatePickerType::class,
                    'field_options' => [
                        'widget' => 'single_text',
                        'format' => 'dd/MM/yyyy',
                    ],
                ],
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
                    'field_type' => ChoiceType::class,
                    'field_options' => [
                        'choices' => InvoiceYearMonthEnum::getMonthEnumArray(),
                        'expanded' => false,
                        'multiple' => false,
                    ],
                ]
            )
            ->add(
                'trainingCenter',
                null,
                [
                    'label' => 'backend.admin.class_group.training_center',
                    'field_type' => EntityType::class,
                    'field_options' => [
                        'class' => TrainingCenter::class,
                        'query_builder' => $this->em->getRepository(TrainingCenter::class)->getEnabledSortedByNameQB(),
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
                        'query_builder' => $this->em->getRepository(BankCreditorSepa::class)->getEnabledSortedByNameQB(),
                    ],
                ]
            )
            ->add(
                'baseAmount',
                null,
                [
                    'label' => 'backend.admin.invoice.baseAmount',
                ]
            )
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add(
                'createdAt',
                null,
                [
                    'label' => 'backend.admin.date',
                    'format' => AbstractBase::DATETIME_STRING_FORMAT,
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
                    'editable' => false,
                    'sortable' => false,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'month',
                null,
                [
                    'label' => 'backend.admin.invoice.month',
                    'editable' => false,
                    'sortable' => false,
                    'template' => 'Admin/Cells/list__cell_event_month.html.twig',
                ]
            )
            ->add(
                'trainingCenter',
                null,
                [
                    'label' => 'backend.admin.class_group.training_center',
                    'editable' => false,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'bankCreditorSepa',
                null,
                [
                    'label' => 'backend.admin.bank.creditor_bank_name',
                    'editable' => false,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'baseAmount',
                null,
                [
                    'label' => 'backend.admin.invoice.baseAmount',
                    'template' => 'Admin/Cells/list__cell_receipt_amount.html.twig',
                    'editable' => false,
                    'sortable' => false,
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                ]
            )
            ->add(
                ListMapper::NAME_ACTIONS,
                null,
                [
                    'label' => 'backend.admin.actions',
                    'header_style' => 'width:248px',
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'delete' => [
                            'template' => 'Admin/Buttons/list__action_delete_button.html.twig',
                        ],
                    ],
                ]
            )
        ;
    }
}
