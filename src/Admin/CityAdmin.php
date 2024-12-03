<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use App\Entity\Province;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

final class CityAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'City';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::ASC;
        $sortValues[DatagridInterface::SORT_BY] = 'name';
    }

    public function generateBaseRoutePattern(bool $isChildAdmin = false): string
    {
        return 'administrations/city';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        parent::configureRoutes($collection);
        $collection->remove('delete');
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray('backend.admin.general', 3))
            ->add(
                'postalCode',
                null,
                [
                    'label' => 'backend.admin.city.postalCode',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.city.name',
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray('backend.admin.controls', 3))
            ->add(
                'province',
                EntityType::class,
                [
                    'label' => 'backend.admin.city.province',
                    'required' => true,
                    'class' => Province::class,
                    'choice_label' => 'name',
                    'query_builder' => $this->em->getRepository(Province::class)->getEnabledSortedByNameQB(),
                ]
            )
            ->add(
                'enabled',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.enabled',
                    'required' => false,
                ]
            )
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add(
                'postalCode',
                null,
                [
                    'label' => 'backend.admin.city.postalCode',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.city.name',
                ]
            )
            ->add(
                'province',
                null,
                [
                    'label' => 'backend.admin.city.province',
                ]
            )
            ->add(
                'enabled',
                null,
                [
                    'label' => 'backend.admin.enabled',
                ]
            )
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add(
                'postalCode',
                null,
                [
                    'label' => 'backend.admin.city.postalCode',
                    'editable' => true,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.city.name',
                    'editable' => true,
                ]
            )
            ->add(
                'province',
                null,
                [
                    'label' => 'backend.admin.city.province',
                    'editable' => true,
                    'associated_property' => 'name',
                    'sortable' => true,
                    'sort_field_mapping' => ['fieldName' => 'name'],
                    'sort_parent_association_mappings' => [['fieldName' => 'province']],
                ]
            )
            ->add(
                'enabled',
                null,
                [
                    'label' => 'backend.admin.enabled',
                    'editable' => true,
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                ListMapper::NAME_ACTIONS,
                null,
                [
                    'label' => 'backend.admin.actions',
                    'header_style' => 'width:63px',
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'edit' => ['template' => 'Admin/Buttons/list__action_edit_button.html.twig'],
                    ],
                ]
            )
        ;
    }

    public function configureExportFields(): array
    {
        return [
            'postalCode',
            'name',
            'province',
            'enabled',
        ];
    }
}
