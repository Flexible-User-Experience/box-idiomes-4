<?php

namespace App\Form\Type;

use App\Form\Model\GenerateInvoiceModel;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateInvoiceYearMonthChooserType extends GenerateReceiptYearMonthChooserType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => GenerateInvoiceModel::class,
            ]
        );
    }
}
