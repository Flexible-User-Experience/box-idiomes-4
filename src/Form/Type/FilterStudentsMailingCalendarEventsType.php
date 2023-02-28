<?php

namespace App\Form\Type;

use App\Form\Model\FilterCalendarEventModel;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterStudentsMailingCalendarEventsType extends FilterCalendarEventsType
{
    public const SESSION_KEY = 'filter_students_mailing_calendar_events_form_data';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('submit')
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'mailing.step_1_button',
                    'attr' => [
                        'class' => 'btn btn-success',
                    ],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'method' => Request::METHOD_POST,
                'action' => $this->rs->generate('admin_app_student_mailing'),
                'data_class' => FilterCalendarEventModel::class,
                'attr' => [
                    'class' => 'form-inline',
                ],
            ]
        );
    }
}
