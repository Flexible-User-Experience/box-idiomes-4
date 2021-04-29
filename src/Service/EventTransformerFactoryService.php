<?php

namespace App\Service;

use App\Entity\Event as AppEvent;
use App\Entity\EventFullCalendar;
use App\Entity\TeacherAbsence;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class EventTransformerFactoryService.
 *
 * @category Service
 */
class EventTransformerFactoryService
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Methods.
     */

    /**
     * EventTransformerFactoryService constructor.
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Classroom event builder.
     *
     * @return EventFullCalendar
     */
    public function build(AppEvent $appEvent)
    {
        $eventFullCalendar = new EventFullCalendar($appEvent->getCalendarTitle(), $appEvent->getBegin());
        $eventFullCalendar->setEnd($appEvent->getEnd());
        $eventFullCalendar->setAllDay(false);
        $eventFullCalendar->setOptions([
            'background' => $appEvent->getGroup()->getColor(),
            'text' => '#FFFFFF',
            'color' => $appEvent->getGroup()->getColor(),
            'url' => $this->router->generate('admin_app_event_edit', ['id' => $appEvent->getId()], UrlGeneratorInterface::ABSOLUTE_PATH),
        ]);

        return $eventFullCalendar;
    }

    /**
     * Teacher absence builder.
     *
     * @return EventFullCalendar
     */
    public function buildTeacherAbsence(TeacherAbsence $teacherAbsence)
    {
        $eventFullCalendar = new EventFullCalendar($teacherAbsence->getCalendarTitle(), $teacherAbsence->getDay());
        $eventFullCalendar->setEnd($teacherAbsence->getDay());
        $eventFullCalendar->setAllDay(true);
        $eventFullCalendar->setOptions([
            'background' => '#FA141B',
            'text' => '#FFFFFF',
            'color' => '#FA141B',
            'url' => $this->router->generate('admin_app_teacherabsence_edit', ['id' => $teacherAbsence->getId()], UrlGeneratorInterface::ABSOLUTE_PATH),
        ]);

        return $eventFullCalendar;
    }
}
