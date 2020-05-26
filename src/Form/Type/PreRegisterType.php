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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PreRegisterType.
 *
 * @category FormType
 */
class PreRegisterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'label' => 'frontend.forms.preregister.name',
                    'required' => true,
                    'attr' => array(
                        'tabindex' => 1,
                    ),
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )
            ->add(
                'surname',
                TextType::class,
                array(
                    'label' => 'frontend.forms.preregister.surname',
                    'required' => true,
                    'attr' => array(
                        'tabindex' => 2,
                    ),
                    'constraints' => array(
                        new Assert\NotBlank(),
                    ),
                )
            )
            ->add(
                'phone',
                TextType::class,
                array(
                    'label' => 'frontend.forms.preregister.phone',
                    'required' => false,
                    'attr' => array(
                        'tabindex' => 3,
                    ),
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label' => 'frontend.forms.preregister.email',
                    'required' => true,
                    'attr' => array(
                        'tabindex' => 4,
                    ),
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Email(array(
                            'strict' => true,
                            'checkMX' => true,
                            'checkHost' => true,
                        )),
                    ),
                )
            )
            ->add(
                'age',
                TextType::class,
                array(
                    'label' => 'frontend.forms.preregister.age',
                    'required' => false,
                    'attr' => array(
                        'tabindex' => 5,
                    ),
                )
            )
            ->add(
                'courseLevel',
                TextType::class,
                array(
                    'label' => 'frontend.forms.preregister.course_level',
                    'required' => false,
                    'attr' => array(
                        'tabindex' => 6,
                    ),
                )
            )
            ->add(
                'preferredTimetable',
                TextType::class,
                array(
                    'label' => 'frontend.forms.preregister.preferred_timetable',
                    'required' => false,
                    'attr' => array(
                        'tabindex' => 7,
                    ),
                )
            )
            ->add(
                'previousAcademy',
                TextType::class,
                array(
                    'label' => 'frontend.forms.preregister.previous_academy',
                    'required' => false,
                    'attr' => array(
                        'tabindex' => 8,
                    ),
                )
            )
            ->add(
                'comments',
                TextareaType::class,
                array(
                    'label' => 'frontend.forms.preregister.comments',
                    'required' => false,
                    'attr' => array(
                        'tabindex' => 9,
                        'rows' => 3,
                    ),
                )
            )
            ->add(
                'season',
                HiddenType::class,
                array(
                    'label' => 'frontend.forms.preregister.season',
                    'required' => false,
                )
            )
            ->add(
                'privacy',
                CheckboxType::class,
                array(
                    'required' => true,
                    'label' => 'frontend.forms.privacy',
                    'mapped' => false,
                    'attr' => array(
                        'tabindex' => 10,
                    ),
                )
            )
            ->add(
                'send',
                SubmitType::class,
                array(
                    'label' => 'frontend.forms.preregister.submit',
                    'attr' => array(
                        'class' => 'btn-newsletter',
                        'tabindex' => 11,
                    ),
                )
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => PreRegister::class,
            )
        );
    }
}
