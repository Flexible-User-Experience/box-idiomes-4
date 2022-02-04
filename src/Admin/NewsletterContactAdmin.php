<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\DoctrineORMAdminBundle\Filter\DateTimeFilter;
use Sonata\Form\Type\DateTimePickerType;

final class NewsletterContactAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'NewsletterContact';
    protected $baseRoutePattern = 'contacts/newsletter';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::DESC;
        $sortValues[DatagridInterface::SORT_BY] = 'createdAt';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('answer', $this->getRouterIdParameter().'/answer')
            ->remove('create')
            ->remove('edit')
            ->remove('delete')
            ->remove('batch')
            ->remove('batch');
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'createdAt',
                DateTimeFilter::class,
                [
                    'label' => 'backend.admin.date',
                    'field_type' => DateTimePickerType::class,
                    'field_options' => [
                        'widget' => 'single_text',
                        'format' => 'dd-MM-yyyy HH:mm',
                    ],
//                    'format' => 'd-m-Y H:i',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.contact.email',
                ]
            )
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add(
                'createdAt',
                'date',
                [
                    'label' => 'backend.admin.contact.date',
                    'format' => 'd/m/Y H:i',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.contact.email',
                ]
            )
        ;
    }

    public function configureExportFields(): array
    {
        return [
            'createdAtString',
            'email',
        ];
    }
}
