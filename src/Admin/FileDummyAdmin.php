<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

final class FileDummyAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'FileDummy';
    protected $baseRoutePattern = 'fitxers';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::ASC;
        $sortValues[DatagridInterface::SORT_BY] = 'name';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
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
