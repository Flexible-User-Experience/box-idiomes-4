<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\RouterInterface;

class GenerateInvoiceType extends GenerateInvoiceYearMonthChooserType
{
    private RouterInterface $rs;

    public function __construct(RouterInterface $rs)
    {
        $this->rs = $rs;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('preview')
            ->add(
                'items',
                CollectionType::class,
                [
                    'label' => 'backend.admin.invoice.items',
                    'allow_extra_fields' => true,
                    'required' => false,
                    'entry_type' => GenerateInvoiceItemType::class,
                    'by_reference' => false,
                    'entry_options' => [
                        'label' => false,
                    ],
                ]
            )
            ->add(
                'generate',
                SubmitType::class,
                [
                    'label' => 'backend.admin.invoice.generate',
                    'attr' => [
                        'class' => 'btn btn-success',
                    ],
                ]
            )
            ->setAction($this->rs->generate('admin_app_invoice_creator'))
        ;
    }
}
