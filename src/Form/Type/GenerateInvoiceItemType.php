<?php

namespace App\Form\Type;

use App\Form\Model\GenerateInvoiceItemModel;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateInvoiceItemType extends GenerateReceiptItemType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => GenerateInvoiceItemModel::class,
            ]
        );
    }
}
