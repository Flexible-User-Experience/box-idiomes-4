<?php

namespace App\Form\Type;

use App\Entity\Event;
use App\Manager\EventManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EventBatchRemoveType.
 *
 * @category FormType
 */
class EventBatchRemoveType extends AbstractType
{
    /**
     * @var EventManager
     */
    private $em;

    /**
     * Methods.
     */

    /**
     * EventBatchRemoveType constructor.
     */
    public function __construct(EventManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Event $event */
        $event = $options['event'];
        /** @var Event $lastEvent */
        $lastEvent = $this->em->getLastEventOf($event);
        $builder
            ->add(
                'range',
                ChoiceType::class,
                [
                    'mapped' => false,
                    'label' => 'backend.admin.event.batch_delete.range',
                    'required' => true,
                    'choices' => $this->em->getInclusiveRangeChoices($event),
                    'data' => is_null($lastEvent) ? $event->getId() : $this->em->getLastEventOf($event)->getId(),
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Event::class,
                'event' => null,
            ]
        );
    }
}
