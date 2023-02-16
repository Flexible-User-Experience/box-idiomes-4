<?php

namespace App\Form\Type;

use App\Entity\ClassGroup;
use App\Entity\Teacher;
use App\Enum\EventClassroomTypeEnum;
use App\Form\Model\FilterCalendarEventModel;
use App\Repository\ClassGroupRepository;
use App\Repository\TeacherRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class FilterCalendarEventsType extends AbstractType
{
    public const SESSION_KEY = 'filter_calendar_events_form_data';

    private RouterInterface $rs;
    private TeacherRepository $tr;
    private ClassGroupRepository $cgr;

    public function __construct(RouterInterface $rs, TeacherRepository $tr, ClassGroupRepository $cgr)
    {
        $this->rs = $rs;
        $this->tr = $tr;
        $this->cgr = $cgr;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'classroom',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.event.classroom',
                    'placeholder' => 'backend.admin.event.classroom',
                    'choices' => EventClassroomTypeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                ]
            )
            ->add(
                'teacher',
                EntityType::class,
                [
                    'label' => 'backend.admin.event.teacher',
                    'placeholder' => 'backend.admin.event.teacher',
                    'class' => Teacher::class,
                    'choice_label' => 'name',
                    'query_builder' => $this->tr->getEnabledSortedByNameQB(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                ]
            )
            ->add(
                'group',
                EntityType::class,
                [
                    'label' => 'backend.admin.event.group',
                    'placeholder' => 'backend.admin.event.group',
                    'class' => ClassGroup::class,
                    'query_builder' => $this->cgr->getEnabledSortedByCodeQB(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'backend.admin.filter_calendar_by',
                    'attr' => [
                        'class' => 'btn btn-warning pull-right',
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
                'action' => $this->rs->generate('admin_app_filedummy_filterCalendar'),
                'data_class' => FilterCalendarEventModel::class,
            ]
        );
    }
}
