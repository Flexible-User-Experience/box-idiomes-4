<?php

namespace App\Form\Type;

use App\Entity\PreRegister;
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

/**
 * Class PreRegisterType.
 *
 * @category FormType
 */
class PreRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
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
                        new Assert\Email([
                            'strict' => true,
                            'checkMX' => true,
                            'checkHost' => true,
                        ]),
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
                        'tabindex' => 10,
                    ],
                ]
            )
            ->add(
                'send',
                SubmitType::class,
                [
                    'label' => 'frontend.forms.preregister.submit',
                    'attr' => [
                        'class' => 'btn-newsletter',
                        'tabindex' => 11,
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => PreRegister::class,
            ]
        );
    }
}
