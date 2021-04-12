<?php

namespace App\Form\Type;

use App\Enum\ReceiptYearMonthEnum;
use App\Form\Model\GenerateReceiptModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerateReceiptYearMonthChooserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'year',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.invoice.year',
                    'required' => true,
                    'choices' => ReceiptYearMonthEnum::getYearEnumArray(),
                ]
            )
            ->add(
                'month',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.invoice.month',
                    'required' => true,
                    'choices' => ReceiptYearMonthEnum::getMonthEnumArray(),
                ]
            )
            ->add(
                'preview',
                SubmitType::class,
                [
                    'label' => 'backend.admin.receipt.preview_invoice',
                    'attr' => [
                        'class' => 'btn btn-warning',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => GenerateReceiptModel::class,
            ]
        );
    }
}
