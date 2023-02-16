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
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterCalendarEventsType extends AbstractType
{
    private TeacherRepository $tr;
    private ClassGroupRepository $cgr;

    public function __construct(TeacherRepository $tr, ClassGroupRepository $cgr)
    {
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
                'data_class' => FilterCalendarEventModel::class,
            ]
        );
    }
}
