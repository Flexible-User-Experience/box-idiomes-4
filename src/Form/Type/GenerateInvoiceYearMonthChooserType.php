<?php

namespace App\Form\Type;

use App\Form\Model\GenerateInvoiceModel;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GenerateInvoiceYearMonthChooserType.
 *
 * @category FormType
 */
class GenerateInvoiceYearMonthChooserType extends GenerateReceiptYearMonthChooserType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => GenerateInvoiceModel::class,
            ]
        );
    }
}
