<?php

namespace App\Form\Type;

use App\Entity\ContactMessage;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => false,
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'frontend.forms.name',
                        'class' => 'common-fields',
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => false,
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'frontend.forms.email',
                        'class' => 'common-fields',
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Email([
                            'strict' => true,
                            'checkMX' => true,
                            'checkHost' => true,
                        ]),
                    ],
                ]
            )
            ->add(
                'phone',
                TextType::class,
                [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'frontend.forms.phone',
                        'class' => 'common-fields',
                    ],
                ]
            )
            ->add(
                'message',
                TextareaType::class,
                [
                    'label' => 'frontend.forms.message',
                    'required' => true,
                    'attr' => [
                        'rows' => 5,
                        'class' => 'message-field',
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'privacy',
                CheckboxType::class,
                [
                    'required' => true,
                    'label' => 'frontend.forms.privacy',
                    'mapped' => false,
                ]
            )
            ->add(
                'captcha',
                EWZRecaptchaType::class,
                [
                    'label' => ' ',
                    'attr' => [
                        'options' => [
                            'theme' => 'light',
                            'type' => 'image',
                            'size' => 'normal',
                        ],
                    ],
                    'mapped' => false,
                    'constraints' => [
                        new RecaptchaTrue(),
                    ],
                ]
            )
            ->add(
                'send',
                SubmitType::class,
                [
                    'label' => 'frontend.forms.send',
                    'attr' => [
                        'class' => 'btn-newsletter',
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
