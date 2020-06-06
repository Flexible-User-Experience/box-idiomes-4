<?php

namespace App\Form\Type;

use Exception;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class GenerateReceiptType.
 *
 * @category FormType
 */
class GenerateReceiptType extends GenerateReceiptYearMonthChooserType
{
    const NAME = 'generate_receipt';

    /**
     * @var RouterInterface
     */
    private $rs;

    /**
     * Methods.
     */

    /**
     * GenerateReceiptType constructor.
     *
     * @param RouterInterface $rs
     */
    public function __construct(RouterInterface $rs)
    {
        $this->rs = $rs;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('preview')
            // warning: this collection can produce a large input form fields, consider to increase max_input_vars PHP config
            ->add(
                'items',
                CollectionType::class,
                array(
                    'label' => 'backend.admin.invoice.items',
                    'allow_extra_fields' => true,
                    'required' => false,
                    'entry_type' => GenerateReceiptItemType::class,
                    'by_reference' => false,
                    'entry_options' => array(
                        'label' => false,
                    ),
                )
            )
            ->add(
                'generate',
                SubmitType::class,
                array(
                    'label' => 'backend.admin.receipt.generate',
                    'attr' => array(
                        'class' => 'btn btn-success',
                    ),
                )
            )
            ->add(
                'generate_and_send',
                SubmitType::class,
                array(
                    'label' => 'backend.admin.receipt.generate_and_send',
                    'attr' => array(
                        'class' => 'btn btn-success',
                    ),
                )
            )
            ->setAction($this->rs->generate('admin_app_receipt_creator'))
        ;
    }
}
