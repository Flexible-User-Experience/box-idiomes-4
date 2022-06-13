<?php

namespace App\Form\Type;

use App\Entity\PreRegister;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaV3Type;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrueV3 as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PreRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'frontend.forms.preregister.name',
                    'required' => true,
                    'attr' => [
                        'tabindex' => 1,
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'surname',
                TextType::class,
                [
                    'label' => 'frontend.forms.preregister.surname',
                    'required' => true,
                    'attr' => [
                        'tabindex' => 2,
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ]
            )
            ->add(
                'phone',
                TextType::class,
                [
                    'label' => 'frontend.forms.preregister.phone',
                    'required' => true,
                    'attr' => [
                        'tabindex' => 3,
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
                    'label' => 'frontend.forms.preregister.email',
                    'required' => true,
                    'attr' => [
                        'tabindex' => 4,
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Email(),
                    ],
                ]
            )
            ->add(
                'age',
                TextType::class,
                [
                    'label' => 'frontend.forms.preregister.age',
                    'required' => false,
                    'attr' => [
                        'tabindex' => 5,
                    ],
                ]
            )
            ->add(
                'courseLevel',
                TextType::class,
                [
                    'label' => 'frontend.forms.preregister.course_level',
                    'required' => false,
                    'attr' => [
                        'tabindex' => 6,
                    ],
                ]
            )
            ->add(
                'preferredTimetable',
                TextType::class,
                [
                    'label' => 'frontend.forms.preregister.preferred_timetable',
                    'required' => false,
                    'attr' => [
                        'tabindex' => 7,
                    ],
                ]
            )
            ->add(
                'previousAcademy',
                TextType::class,
                [
                    'label' => 'frontend.forms.preregister.previous_academy',
                    'required' => false,
                    'attr' => [
                        'tabindex' => 8,
                    ],
                ]
            )
            ->add(
                'comments',
                TextareaType::class,
                [
                    'label' => 'frontend.forms.preregister.comments',
                    'required' => false,
                    'attr' => [
                        'tabindex' => 9,
                        'rows' => 3,
                    ],
                ]
            )
            ->add(
                'hasBeenPreviousCustomer',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'frontend.forms.preregister.has_been_previous_customer',
                    'attr' => [
                        'tabindex' => 10,
                    ],
                ]
            )
            ->add(
                'season',
                HiddenType::class,
                [
                    'label' => 'frontend.forms.preregister.season',
                    'required' => false,
                ]
            )
            ->add(
                'privacy',
                CheckboxType::class,
                [
                    'required' => true,
                    'label' => 'frontend.forms.privacy',
                    'mapped' => false,
                    'attr' => [
                        'tabindex' => 12,
                    ],
                ]
            )
            ->add(
                'captcha',
                EWZRecaptchaV3Type::class,
                [
                    'label' => false,
                    'action_name' => 'pre_register',
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
                    'label' => 'frontend.forms.preregister.submit',
                    'attr' => [
                        'class' => 'btn btn-warning btn-lg text-white mb-5',
                        'tabindex' => 13,
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => PreRegister::class,
            ]
        );
    }
}
