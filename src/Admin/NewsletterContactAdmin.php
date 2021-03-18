<?php

namespace App\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Filter\DateTimeFilter;
use Sonata\Form\Type\DateTimePickerType;

/**
 * Class NewsletterContactAdmin.
 *
 * @category Admin
 */
class NewsletterContactAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'NewsletterContact';
    protected $baseRoutePattern = 'contacts/newsletter';
    protected $datagridValues = [
        '_sort_by' => 'createdAt',
        '_sort_order' => 'desc',
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('edit')
            ->remove('delete')
            ->remove('batch')
            ->add('answer', $this->getRouterIdParameter().'/answer')
            ->remove('batch');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add(
                'createdAt',
                DateTimeFilter::class,
                [
                    'label' => 'backend.admin.date',
                    'field_type' => DateTimePickerType::class,
                    'format' => 'd-m-Y H:i',
                ],
                null,
                [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy HH:mm',
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

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add(
                'createdAt',
                'date',
                [
                    'label' => 'backend.admin.contact.date',
                    'format' => 'd/m/Y H:i',
                    'header_class' => 'text-center',
                    'row_align' => 'center',
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
}
