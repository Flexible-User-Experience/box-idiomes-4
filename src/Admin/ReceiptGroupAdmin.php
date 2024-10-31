<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\AbstractBase;
use App\Entity\TrainingCenter;
use App\Enum\InvoiceYearMonthEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\Form\Type\CollectionType;
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
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $now = new \DateTimeImmutable();
        $currentYear = $now->format('Y');
        $form
            ->with('backend.admin.receipt.receipt', $this->getFormMdSuccessBoxArray('backend.admin.receipt.receipt', 3))
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
            ->end()
            ->with('backend.admin.invoice.detail', $this->getFormMdSuccessBoxArray('backend.admin.invoice.detail', 3))
            ->add(
                'baseAmount',
                null,
                [
                    'label' => 'backend.admin.invoice.baseAmount',
                    'required' => false,
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls', 3))
            ->add(
                'trainingCenter',
                EntityType::class,
                [
                    'label' => 'backend.admin.class_group.training_center',
                    'required' => true,
                    'class' => TrainingCenter::class,
                    'query_builder' => $this->em->getRepository(TrainingCenter::class)->getEnabledSortedByNameQB(),
                ]
            )
            ->end()
        ;
        if ($this->id($this->getSubject())) { // is edit mode, disable on new subjetcs
            $form
                ->with('backend.admin.receipt.lines', $this->getFormMdSuccessBoxArray('backend.admin.receipt.lines', 12))
                ->add(
                    'receipts',
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

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'id',
                null,
                [
                    'label' => 'backend.admin.receipt.id',
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
                    'label' => 'Data Remesa', // TODO
                    //                    'template' => 'Admin/Cells/list__cell_receipt_date.html.twig',
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
                    'header_class' => 'text-center',
                    'row_align' => 'center',
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
                'trainingCenter',
                null,
                [
                    'label' => 'Centre Formació', // TODO
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
                        'show' => [
                            'template' => 'Admin/Buttons/list__action_show_button.html.twig',
                        ],
                        'delete' => [
                            'template' => 'Admin/Buttons/list__action_delete_button.html.twig',
                        ],
                    ],
                ]
            )
        ;
    }
}
