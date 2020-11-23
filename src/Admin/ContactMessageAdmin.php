<?php

namespace App\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;

/**
 * Class ContactMessageAdmin.
 *
 * @category Admin
 */
class ContactMessageAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'ContactMessage';
    protected $baseRoutePattern = 'contacts/message';
    protected $datagridValues = array(
        '_sort_by' => 'createdAt',
        '_sort_order' => 'desc',
    );

    /**
     * Configure route collection.
     *
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        $collection
            ->remove('create')
            ->remove('edit')
            ->remove('batch')
            ->add('answer', $this->getRouterIdParameter().'/answer');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add(
                'checked',
                null,
                array(
                    'label' => 'backend.admin.contact.checked',
                )
            )
            ->add(
                'createdAt',
                'doctrine_orm_date',
                array(
                    'label' => 'backend.admin.date',
                    'field_type' => DatePickerType::class,
                    'format' => 'd-m-Y',
                ),
                null,
                array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.contact.name',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.contact.email',
                )
            )
            ->add(
                'subject',
                null,
                array(
                    'label' => 'backend.admin.contact.subject',
                )
            )
            ->add(
                'message',
                null,
                array(
                    'label' => 'backend.admin.contact.message',
                )
            )
            ->add(
                'answered',
                null,
                array(
                    'label' => 'backend.admin.contact.answered',
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' => 'backend.admin.contact.description',
                )
            );
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add(
                'checked',
                null,
                array(
                    'label' => 'backend.admin.contact.checked',
                )
            )
            ->add(
                'createdAt',
                'date',
                array(
                    'label' => 'backend.admin.date',
                    'format' => 'd/m/Y H:i',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.contact.name',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.contact.email',
                )
            )
            ->add(
                'message',
                'textarea',
                array(
                    'label' => 'backend.admin.contact.message',
                )
            )
            ->add(
                'answered',
                null,
                array(
                    'label' => 'backend.admin.contact.answered',
                )
            );
        if ($this->getSubject()->getAnswered()) {
            $showMapper
                ->add(
                    'description',
                    'textarea',
                    array(
                        'label' => 'backend.admin.contact.answer',
                    )
                );
        }
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add(
                'checked',
                null,
                array(
                    'label' => 'backend.admin.contact.checked',
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                )
            )
            ->add(
                'createdAt',
                'date',
                array(
                    'label' => 'backend.admin.contact.date',
                    'format' => 'd/m/Y',
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                )
            )
            ->add(
                'name',
                null,
                array(
                    'label' => 'backend.admin.contact.name',
                )
            )
            ->add(
                'email',
                null,
                array(
                    'label' => 'backend.admin.contact.email',
                )
            )
            ->add(
                'answered',
                null,
                array(
                    'label' => 'backend.admin.contact.answered',
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'header_class' => 'text-right',
                    'row_align' => 'right',
                    'actions' => array(
                        'show' => array(
                            'template' => 'Admin/Buttons/list__action_show_button.html.twig',
                        ),
                        'answer' => array(
                            'template' => 'Admin/Cells/list__action_answer.html.twig',
                        ),
                        'delete' => array(
                            'template' => 'Admin/Buttons/list__action_delete_button.html.twig',
                        ),
                    ),
                )
            )
        ;
    }

    /**
     * @return array
     */
    public function configureExportFields(): array
    {
        return array(
            'checked',
            'createdAtString',
            'name',
            'email',
            'subject',
            'message',
            'answered',
            'description',
        );
    }
}
