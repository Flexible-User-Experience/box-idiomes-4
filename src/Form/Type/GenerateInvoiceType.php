<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class GenerateInvoiceType.
 *
 * @category FormType
 */
class GenerateInvoiceType extends GenerateInvoiceYearMonthChooserType
{
    /**
     * @var RouterInterface
     */
    private $rs;

    /**
     * Methods.
     */

    /**
     * GenerateInvoiceType constructor.
     */
    public function __construct(RouterInterface $rs)
    {
        $this->rs = $rs;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
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
