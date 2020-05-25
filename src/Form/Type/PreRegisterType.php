<?php

namespace App\Form\Type;

use App\Entity\PreRegister;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
                )
            )
            ->add(
                'email',
                EmailType::class,
                array(
                    'label' => 'frontend.forms.preregister.email',
                    'required' => true,
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
                )
            )
            ->add(
                'courseLevel',
                TextType::class,
                array(
                    'label' => 'frontend.forms.preregister.course_level',
                    'required' => false,
                )
            )
            ->add(
                'preferredTimetable',
                TextType::class,
                array(
                    'label' => 'frontend.forms.preregister.preferred_timetable',
                    'required' => false,
                )
            )
            ->add(
                'previousAcademy',
                TextType::class,
                array(
                    'label' => 'frontend.forms.preregister.previous_academy',
                    'required' => false,
                )
            )
            ->add(
                'comments',
                TextareaType::class,
                array(
                    'label' => 'frontend.forms.preregister.comments',
                    'required' => false,
                    'attr' => array(
                        'rows' => 3,
                    ),
                )
            )
            ->add(
                'privacy',
                CheckboxType::class,
                array(
                    'required' => true,
                    'label' => 'frontend.forms.privacy',
                    'mapped' => false,
                )
            )
            ->add(
                'send',
                SubmitType::class,
                array(
                    'label' => 'frontend.forms.subscribe',
                    'attr' => array(
                        'class' => 'btn-newsletter',
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
