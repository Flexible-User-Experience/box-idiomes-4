<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class AdminLoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'label' => 'backend.admin.login.username',
                    'required' => true,
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'label' => 'backend.admin.login.password',
                    'required' => true,
                ]
            )
            ->add(
                'target_path',
                HiddenType::class,
                [
                    'label' => 'backend.admin.login.target_path',
                    'required' => true,
                ]
            )
        ;
    }
}
