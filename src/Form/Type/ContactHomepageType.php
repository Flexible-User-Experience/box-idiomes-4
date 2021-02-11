<?php

namespace App\Form\Type;

use App\Entity\NewsletterContact;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ContactHomepageType.
 *
 * @category FormType
 */
class ContactHomepageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'frontend.forms.email',
                        'class' => 'newsletter-email',
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
                    'label' => 'frontend.forms.subscribe',
                    'attr' => [
                        'class' => 'btn-newsletter',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => NewsletterContact::class,
            ]
        );
    }
}
