<?php

namespace App\Form\Type;

use App\Entity\ClassGroup;
use App\Entity\Event;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Enum\EventClassroomTypeEnum;
use App\Manager\EventManager;
use App\Repository\ClassGroupRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    private TeacherRepository $tr;
    private ClassGroupRepository $cgr;
    private StudentRepository $sr;
    private EventManager $em;

    public function __construct(TeacherRepository $tr, ClassGroupRepository $cgr, StudentRepository $sr, EventManager $em)
    {
        $this->tr = $tr;
        $this->cgr = $cgr;
        $this->sr = $sr;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Event $event */
        $event = $options['event'];
        $builder
            ->add(
                'begin',
                DateTimePickerType::class,
                [
                    'label' => 'backend.admin.event.begin',
                    'format' => 'd/M/y H:mm',
                    'required' => true,
                ]
            )
            ->add(
                'end',
                DateTimePickerType::class,
                [
                    'label' => 'backend.admin.event.end',
                    'format' => 'd/M/y H:mm',
                    'required' => true,
                ]
            )
            ->add(
                'classroom',
                ChoiceType::class,
                [
                    'label' => 'backend.admin.event.classroom',
                    'choices' => EventClassroomTypeEnum::getEnumArray(),
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                ]
            )
            ->add(
                'teacher',
                EntityType::class,
                [
                    'label' => 'backend.admin.event.teacher',
                    'required' => true,
                    'class' => Teacher::class,
                    'choice_label' => 'name',
                    'query_builder' => $this->tr->getEnabledSortedByNameQB(),
                ]
            )
            ->add(
                'group',
                EntityType::class,
                [
                    'label' => 'backend.admin.event.group',
                    'required' => true,
                    'class' => ClassGroup::class,
                    'query_builder' => $this->cgr->getEnabledSortedByCodeQB(),
                ]
            )
            ->add(
                'students',
                EntityType::class,
                [
                    'label' => 'backend.admin.event.students',
                    'required' => false,
                    'multiple' => true,
                    'class' => Student::class,
                    'choice_label' => 'fullCanonicalName',
                    'query_builder' => $this->sr->getAllSortedBySurnameQB(),
                ]
            )
            ->add(
                'range',
                ChoiceType::class,
                [
                    'mapped' => false,
                    'label' => 'backend.admin.event.range',
                    'required' => true,
                    'choices' => $this->em->getRangeChoices($event),
                    'data' => $this->em->getLastEventOf($event)?->getId(),
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'event' => null,
        ]);
    }
}
