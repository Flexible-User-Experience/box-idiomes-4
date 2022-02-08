<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

final class ExtraHelperManagerAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'ExtraHelper';
    protected $baseRoutePattern = 'extras';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::ASC;
        $sortValues[DatagridInterface::SORT_BY] = 'name';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('exportCalendarPdfList', 'export-calendar-pdf-list/from/{start}/to/{end}')
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
