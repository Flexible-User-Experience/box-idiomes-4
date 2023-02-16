<?php

namespace App\Service;

use App\Entity\Event as AppEvent;
use App\Entity\EventFullCalendar;
use App\Entity\TeacherAbsence;
use Symfony\Component\Routing\RouterInterface;

class EventTransformerFactoryService
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Classroom event builder.
     */
    public function build(AppEvent $appEvent): EventFullCalendar
    {
        $eventFullCalendar = new EventFullCalendar($appEvent->getCalendarTitle(), $appEvent->getBegin());
        $eventFullCalendar->setEnd($appEvent->getEnd());
        $eventFullCalendar->setAllDay(false);
        $eventFullCalendar->setOptions([
            'background' => $appEvent->getGroup()->getColor(),
            'text' => '#FFFFFF',
            'color' => $appEvent->getGroup()->getColor(),
            'url' => $this->router->generate('admin_app_event_edit', ['id' => $appEvent->getId()]),
        ]);

        return $eventFullCalendar;
    }

    /**
     * Teacher absence builder.
     */
    public function buildTeacherAbsence(TeacherAbsence $teacherAbsence): EventFullCalendar
    {
        $eventFullCalendar = new EventFullCalendar($teacherAbsence->getCalendarTitle(), $teacherAbsence->getDay());
        $eventFullCalendar->setEnd($teacherAbsence->getDay());
        $eventFullCalendar->setAllDay(true);
        $eventFullCalendar->setOptions([
            'background' => '#FA141B',
            'text' => '#FFFFFF',
            'color' => '#FA141B',
            'url' => $this->router->generate('admin_app_teacherabsence_edit', ['id' => $teacherAbsence->getId()]),
        ]);

        return $eventFullCalendar;
    }
}
