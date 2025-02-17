<?php

namespace App\Admin;

use App\Doctrine\Enum\SortOrderTypeEnum;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateFilter;
use Sonata\Form\Type\DatePickerType;

final class ContactMessageAdmin extends AbstractBaseAdmin
{
    protected $classnameLabel = 'ContactMessage';

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::PAGE] = 1;
        $sortValues[DatagridInterface::SORT_ORDER] = SortOrderTypeEnum::DESC;
        $sortValues[DatagridInterface::SORT_BY] = 'createdAt';
    }

    public function generateBaseRoutePattern(bool $isChildAdmin = false): string
    {
        return 'contacts/message';
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('answer', $this->getRouterIdParameter().'/answer')
            ->remove('create')
            ->remove('edit')
            ->remove('batch')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
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
                    'field_options' => [
                        'widget' => 'single_text',
                        'format' => 'dd/MM/yyyy',
                    ],
                ],
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
            )
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
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
            )
        ;
        if ($this->getSubject() && $this->getSubject()->getAnswered()) {
            $show
                ->add(
                    'description',
                    'textarea',
                    [
                        'label' => 'backend.admin.contact.answer',
                    ]
                )
                ->add(
                    'document',
                    null,
                    [
                        'label' => 'backend.admin.contact.attatchment',
                        'template' => 'Admin/ContactMessage/answer_attatchment_file_preview.html.twig',
                    ]
                )
            ;
        }
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
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
                ListMapper::NAME_ACTIONS,
                null,
                [
                    'label' => 'backend.admin.actions',
                    'header_style' => 'width:116px',
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

    public function configureExportFields(): array
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
