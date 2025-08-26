<?php

namespace App\Form\Type;

use App\Entity\ClassGroup;
use App\Entity\Teacher;
use App\Entity\TrainingCenter;
use App\Enum\EventClassroomTypeEnum;
use App\Form\Model\FilterCalendarEventModel;
use App\Repository\ClassGroupRepository;
use App\Repository\TeacherRepository;
use App\Repository\TrainingCenterRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FilterCalendarEventsType extends AbstractType
{
    public const SESSION_KEY = 'filter_calendar_events_form_data';

    protected TranslatorInterface $ts;
    protected RouterInterface $rs;
    protected TeacherRepository $tr;
    protected ClassGroupRepository $cgr;
    protected TrainingCenterRepository $tcr;

    public function __construct(TranslatorInterface $ts, RouterInterface $rs, TeacherRepository $tr, ClassGroupRepository $cgr, TrainingCenterRepository $tcr)
    {
        $this->ts = $ts;
        $this->rs = $rs;
        $this->tr = $tr;
        $this->cgr = $cgr;
        $this->tcr = $tcr;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'classroom',
                ChoiceType::class,
                [
                    'label' => false,
                    'placeholder' => 'backend.admin.event.classroom',
                    'choices' => EventClassroomTypeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'attr' => [
                        'data-placeholder' => $this->ts->trans('backend.admin.event.classroom'),
                    ],
                ]
            )
            ->add(
                'teacher',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.event.teacher',
                    'placeholder' => 'backend.admin.event.teacher',
                    'choices' => $this->tr->getEnabledSortedByName(),
                    'choice_value' => 'id',
                    'choice_label' => function (?Teacher $teacher) {
                        return $teacher ? $teacher->getName() : '';
                    },
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'attr' => [
                        'data-placeholder' => $this->ts->trans('backend.admin.event.teacher'),
                    ],
                ]
            )
            ->add(
                'group',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.event.group',
                    'placeholder' => 'backend.admin.event.group',
                    'choices' => $this->cgr->getEnabledSortedByCode(),
                    'choice_value' => 'id',
                    'choice_label' => function (?ClassGroup $classGroup) {
                        return $classGroup ? $classGroup->__toString() : '';
                    },
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'attr' => [
                        'data-placeholder' => $this->ts->trans('backend.admin.event.group'),
                    ],
                ]
            )
            ->add(
                'trainingCenter',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.class_group.training_center',
                    'placeholder' => 'backend.admin.class_group.training_center',
                    'choices' => $this->tcr->getEnabledSortedByName(),
                    'choice_value' => 'id',
                    'choice_label' => function (?TrainingCenter $trainingCenter) {
                        return $trainingCenter ? $trainingCenter->getName() : '';
                    },
                    'multiple' => false,
                    'expanded' => false,
                    'required' => false,
                    'attr' => [
                        'data-placeholder' => $this->ts->trans('backend.admin.class_group.training_center'),
                    ],
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
        $resolver->setDefaults([
            'method' => Request::METHOD_POST,
            'action' => $this->rs->generate('admin_app_filter_calendar'),
            'data_class' => FilterCalendarEventModel::class,
            'attr' => [
                'class' => 'form-inline',
            ],
        ]);
    }
}
