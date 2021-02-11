<?php

namespace App\Admin;

use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class BankAdmin.
 *
 * @category Admin
 */
class FileDummyAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'FileDummy';
    protected $baseRoutePattern = 'fitxers';
    protected $datagridValues = [
        '_sort_by' => 'name',
        '_sort_order' => 'asc',
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        $collection
            ->add('handler', 'gestor')
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
