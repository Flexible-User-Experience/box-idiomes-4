<?php

namespace App\Form\Type;

use App\Form\Model\GenerateInvoiceItemModel;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GenerateInvoiceItemType.
 *
 * @category FormType
 */
class GenerateInvoiceItemType extends GenerateReceiptItemType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => GenerateInvoiceItemModel::class,
            ]
        );
    }
}
