<?php

namespace App\Admin;

use Sonata\AdminBundle\Route\RouteCollection;

class ExtraHelperManagerAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'ExtraHelper';
    protected $baseRoutePattern = 'extras';
    protected $datagridValues = [
        '_sort_by' => 'name',
        '_sort_order' => 'asc',
    ];

    protected function configureRoutes(RouteCollection $collection): void
    {
        $collection
            ->add('exportCalendarPdfList', 'export-calendar-pdf-list')
            ->remove('list')
            ->remove('create')
            ->remove('edit')
            ->remove('show')
            ->remove('delete')
            ->remove('batch')
            ->remove('export')
        ;
    }
}
