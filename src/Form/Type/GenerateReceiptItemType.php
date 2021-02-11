<?php

namespace App\Form\Type;

use App\Form\Model\GenerateReceiptItemModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GenerateReceiptItemType.
 *
 * @category FormType
 */
class GenerateReceiptItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'studentId',
                IntegerType::class,
                [
                    'label' => 'backend.admin.student.student',
                    'required' => true,
                ]
            )
            ->add(
                'units',
                NumberType::class,
                [
                    'label' => 'backend.admin.invoiceLine.units',
                    'scale' => 1,
                    'grouping' => true,
                    'required' => true,
                ]
            )
            ->add(
                'unitPrice',
                NumberType::class,
                [
                    'label' => 'backend.admin.invoiceLine.priceUnit',
                    'scale' => 2,
                    'grouping' => true,
                    'required' => true,
                ]
            )
            ->add(
                'discount',
                NumberType::class,
                [
                    'label' => 'backend.admin.invoiceLine.discount',
                    'scale' => 2,
                    'grouping' => true,
                    'required' => true,
                ]
            )
            ->add(
                'isReadyToGenerate',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.invoiceLine.isReadyToGenerate',
                    'required' => false,
                ]
            )
            ->add(
                'isPreviouslyGenerated',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.invoiceLine.isPreviouslyGenerated',
                    'required' => false,
                ]
            )
            ->add(
                'isPrivateLessonType',
                CheckboxType::class,
                [
                    'label' => 'backend.admin.invoiceLine.isPrivateLessonType',
                    'required' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => GenerateReceiptItemModel::class,
            ]
        );
    }
}
