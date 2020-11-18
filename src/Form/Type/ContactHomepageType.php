<?php

namespace App\Form\Type;

use App\Entity\NewsletterContact;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ContactHomepageType.
 *
 * @category FormType
 */
class ContactHomepageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                array(
                    'required' => true,
                    'attr' => array(
                        'placeholder' => 'frontend.forms.email',
                        'class' => 'newsletter-email',
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
                'privacy',
                CheckboxType::class,
                array(
                    'required' => true,
                    'label' => 'frontend.forms.privacy',
                    'mapped' => false,
                )
            )
            ->add(
                'captcha',
                EWZRecaptchaType::class,
                array(
                    'label' => ' ',
                    'attr' => array(
                        'options' => array(
                            'theme' => 'light',
                            'type' => 'image',
                            'size' => 'normal',
                        ),
                    ),
                    'mapped' => false,
                    'constraints' => array(
                        new RecaptchaTrue(),
                    ),
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
                'data_class' => NewsletterContact::class,
            )
        );
    }
}
