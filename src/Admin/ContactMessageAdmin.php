<?php

namespace App\Admin;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
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
    protected $datagridValues = [
        '_sort_by' => 'createdAt',
        '_sort_order' => 'desc',
    ];

    /**
     * Configure route collection.
     */
    protected function configureRoutes(RouteCollection $collection): void
    {
        $collection
            ->remove('create')
            ->remove('edit')
            ->remove('batch')
            ->add('answer', $this->getRouterIdParameter().'/answer');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add(
                'checked',
                null,
                [
                    'label' => 'backend.admin.contact.checked',
                ]
            )
            ->add(
                'createdAt',
                DateFilter::class,
                [
                    'label' => 'backend.admin.date',
                    'field_type' => DatePickerType::class,
                    'format' => 'd-m-Y',
                ],
                null,
                [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.contact.name',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.contact.email',
                ]
            )
            ->add(
                'subject',
                null,
                [
                    'label' => 'backend.admin.contact.subject',
                ]
            )
            ->add(
                'message',
                null,
                [
                    'label' => 'backend.admin.contact.message',
                ]
            )
            ->add(
                'answered',
                null,
                [
                    'label' => 'backend.admin.contact.answered',
                ]
            )
            ->add(
                'description',
                null,
                [
                    'label' => 'backend.admin.contact.description',
                ]
            );
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add(
                'checked',
                null,
                [
                    'label' => 'backend.admin.contact.checked',
                ]
            )
            ->add(
                'createdAt',
                'date',
                [
                    'label' => 'backend.admin.date',
                    'format' => 'd/m/Y H:i',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.contact.name',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.contact.email',
                ]
            )
            ->add(
                'message',
                'textarea',
                [
                    'label' => 'backend.admin.contact.message',
                ]
            )
            ->add(
                'answered',
                null,
                [
                    'label' => 'backend.admin.contact.answered',
                ]
            );
        if ($this->getSubject()->getAnswered()) {
            $showMapper
                ->add(
                    'description',
                    'textarea',
                    [
                        'label' => 'backend.admin.contact.answer',
                    ]
                );
        }
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add(
                'checked',
                null,
                [
                    'label' => 'backend.admin.contact.checked',
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'createdAt',
                'date',
                [
                    'label' => 'backend.admin.contact.date',
                    'format' => 'd/m/Y',
                    'header_class' => 'text-center',
                    'row_align' => 'center',
                ]
            )
            ->add(
                'name',
                null,
                [
                    'label' => 'backend.admin.contact.name',
                ]
            )
            ->add(
                'email',
                null,
                [
                    'label' => 'backend.admin.contact.email',
                ]
            )
            ->add(
                'answered',
                null,
                [
                    'label' => 'backend.admin.contact.answered',
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
                        'show' => [
                            'template' => 'Admin/Buttons/list__action_show_button.html.twig',
                        ],
                        'answer' => [
                            'template' => 'Admin/Cells/list__action_answer.html.twig',
                        ],
                        'delete' => [
                            'template' => 'Admin/Buttons/list__action_delete_button.html.twig',
                        ],
                    ],
                ]
            )
        ;
    }

    public function getExportFields(): array
    {
        return [
            'checked',
            'createdAtString',
            'name',
            'email',
            'subject',
            'message',
            'answered',
            'description',
        ];
    }
}
