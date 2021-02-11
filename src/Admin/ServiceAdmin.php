<?php

namespace App\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * Class ServiceAdmin.
 *
 * @category Admin
 */
class ServiceAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'Service';
    protected $baseRoutePattern = 'services/service';
    protected $datagridValues = [
        '_sort_by' => 'position',
        '_sort_order' => 'asc',
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        $collection
            ->remove('batch');
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with('backend.admin.general', $this->getFormMdSuccessBoxArray(6))
            ->add(
                'imageFile',
                FileType::class,
                [
                    'label' => 'backend.admin.image',
                    'help' => $this->getImageHelperFormMapperWithThumbnail(),
                    'required' => false,
                ]
            )
            ->add(
                'title',
                null,
                [
                    'label' => 'backend.admin.service.title',
                ]
            )
            ->add(
                'description',
                CKEditorType::class,
                [
                    'label' => 'backend.admin.description',
                    'config_name' => 'my_config',
                ]
            )
            ->end()
            ->with('backend.admin.controls', $this->getFormMdSuccessBoxArray(3))
            ->add(
                'position',
                null,
                [
                    'label' => 'backend.admin.position',
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
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add(
                'position',
                null,
                [
                    'label' => 'backend.admin.position',
                ]
            )
            ->add(
                'title',
                null,
                [
                    'label' => 'backend.admin.service.title',
                ]
            )
            ->add(
                'description',
                null,
                [
                    'label' => 'backend.admin.description',
                ]
            )
            ->add(
                'enabled',
                null,
                [
                    'label' => 'backend.admin.enabled',
                ]
            );
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add(
                'position',
                'decimal',
                [
                    'label' => 'backend.admin.position',
                    'editable' => true,
                ]
            )
            ->add(
                'image',
                null,
                [
                    'label' => 'backend.admin.image',
                    'template' => 'Admin/Cells/list__cell_image_field.html.twig',
                ]
            )
            ->add(
                'title',
                null,
                [
                    'label' => 'backend.admin.service.title',
                    'editable' => true,
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
                        'delete' => ['template' => 'Admin/Buttons/list__action_delete_button.html.twig'],
                    ],
                    'label' => 'backend.admin.actions',
                ]
            )
        ;
    }

    public function getExportFields(): array
    {
        return [
            'position',
            'title',
            'description',
            'enabled',
        ];
    }
}
