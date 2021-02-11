<?php

namespace App\Admin;

use App\Entity\Province;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class CityAdmin.
 *
 * @category Admin
 */
class CityAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'City';
    protected $baseRoutePattern = 'administrations/city';
    protected $datagridValues = [
        '_sort_by' => 'name',
        '_sort_order' => 'asc',
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        parent::configureRoutes($collection);
        $collection->remove('delete');
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(3))
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
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'province',
                EntityType::class,
                [
                    'label' => 'backend.admin.city.province',
                    'required' => true,
                    'class' => Province::class,
                    'choice_label' => 'name',
                    'query_builder' => $this->getConfigurationPool()->getContainer()->get('app.province_repository')->getEnabledSortedByNameQB(),
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

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
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

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add(
                'postalCode',
                null,
                [
                    'label' => 'backend.admin.city.postalCode',
                    'editable' => true,
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
                '_action',
                'actions',
                [
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => [
                        'edit' => ['template' => 'Admin/Buttons/list__action_edit_button.html.twig'],
                    ],
                    'label' => 'Accions',
                ]
            )
        ;
    }
}
