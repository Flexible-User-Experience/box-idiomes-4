<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class MailingStudentsNotificationMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'message',
                TextareaType::class,
                [
                    'label' => 'mailing.message',
                    'required' => true,
                    'attr' => [
                        'rows' => 16,
                        'style' => 'resize:vertical',
                    ],
                ]
            )
            ->add(
                'send',
                SubmitType::class,
                [
                    'label' => 'mailing.submit',
                    'attr' => [
                        'style' => 'margin-top: 20px',
                        'class' => 'btn-success pull-right',
                    ],
                ]
            )
        ;
    }
}
