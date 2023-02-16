<?php

namespace App\Form\Type;

use App\Entity\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ContactMessageAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'backend.admin.contact.answer',
                    'required' => true,
                    'attr' => [
                        'rows' => 6,
                        'style' => 'margin-bottom: 20px',
                    ],
                ]
            )
            ->add(
                'documentFile',
                DropzoneType::class,
                [
                    'label' => 'backend.admin.contact.attatchment',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'backend.admin.contact.attatchment_help',
                    ],
                ]
            )
            ->add(
                'send',
                SubmitType::class,
                [
                    'label' => 'backend.admin.submit',
                    'attr' => [
                        'style' => 'margin-top: 20px',
                        'class' => 'btn-primary',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => ContactMessage::class,
            ]
        );
    }
}
