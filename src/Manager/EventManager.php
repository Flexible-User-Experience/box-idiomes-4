<?php

namespace App\Manager;

use App\Entity\AbstractBase;
use App\Entity\Event;
use App\Entity\Student;
use App\Entity\Tariff;
use App\Model\ExportCalendarToList;
use App\Model\ExportCalendarToListDayHourItem;
use App\Model\ExportCalendarToListDayItem;
use App\Repository\EventRepository;
use App\Repository\TariffRepository;

class EventManager
{
    private EventRepository $er;
    private TariffRepository $tr;

    public function __construct(EventRepository $er, TariffRepository $tr)
    {
        $this->er = $er;
        $this->tr = $tr;
    }

    public function getFirstEventOf(Event $event): ?Event
    {
        $iteratedEvent = null;
        if (!is_null($event->getPrevious())) {
            $iteratedEvent = $event;
            while (!is_null($iteratedEvent->getPrevious())) {
                $iteratedEvent = $iteratedEvent->getPrevious();
            }
        }

        return $iteratedEvent;
    }

    public function getLastEventOf(Event $event): ?Event
    {
        $iteratedEvent = null;
        if (!is_null($event->getNext())) {
            $iteratedEvent = $event;
            while (!is_null($iteratedEvent->getNext())) {
                $iteratedEvent = $iteratedEvent->getNext();
            }
        }

        return $iteratedEvent;
    }

    public function getRelatedEventsAmountOf(Event $event): int
    {
        $amount = 0;
        if (!is_null($event->getNext())) {
            $iteratedEvent = $event;
            while (!is_null($iteratedEvent->getNext())) {
                $iteratedEvent = $iteratedEvent->getNext();
                ++$amount;
            }
        }

        return $amount;
    }

    public function getTotalRelatedEventsAmountOf(Event $event): int
    {
        $amount = 0;
        if (!is_null($event->getPrevious())) {
            $iteratedEvent = $this->getFirstEventOf($event);
        } else {
            $iteratedEvent = $event;
        }
        if (!is_null($event->getNext())) {
            while (!is_null($iteratedEvent->getNext())) {
                $iteratedEvent = $iteratedEvent->getNext();
                ++$amount;
            }
        }

        return $amount;
    }

    public function getProgressBarPercentilesOf(Event $event): array
    {
        $progressBarPercentiles = [];
        $total = $this->getTotalRelatedEventsAmountOf($event);
        $involved = $this->getRelatedEventsAmountOf($event);
        if (0 !== $total) {
            $progressBarPercentiles['last'] = round(($involved * 55) / $total) + 15;
            $progressBarPercentiles['first'] = 85 - $progressBarPercentiles['last'];
        } else {
            $progressBarPercentiles['last'] = 15;
            $progressBarPercentiles['first'] = 85;
        }

        return $progressBarPercentiles;
    }

    public function getRangeChoices(Event $event): array
    {
        $result = [];
        if (!is_null($event->getNext())) {
            $iteratedEvent = $event;
            while (!is_null($iteratedEvent->getNext())) {
                $iteratedEvent = $iteratedEvent->getNext();
                $result[$iteratedEvent->getId()] = $iteratedEvent->getBegin()->format(AbstractBase::DATETIME_STRING_FORMAT);
            }
        }

        return array_flip($result);
    }

    public function getInclusiveRangeChoices(Event $event): array
    {
        $result = [];
        $result[$event->getId()] = $event->getBegin()->format(AbstractBase::DATETIME_STRING_FORMAT);
        if (!is_null($event->getNext())) {
            $iteratedEvent = $event;
            while (!is_null($iteratedEvent->getNext())) {
                $iteratedEvent = $iteratedEvent->getNext();
                $result[$iteratedEvent->getId()] = $iteratedEvent->getBegin()->format(AbstractBase::DATETIME_STRING_FORMAT);
            }
        }

        return array_flip($result);
    }

    /**
     * @param Event[]|array $events
     *
     * @return bool true if there is at least one event with only one student in class, false elsewhere because is a shared private class
     */
    public function decidePrivateLessonsTariff(array $events): bool
    {
        $isPrivateLesson = false;
        /** @var Event $event */
        foreach ($events as $event) {
            if (1 === count($event->getStudents())) {
                $isPrivateLesson = true;

                break;
            }
        }

        return $isPrivateLesson;
    }

    /**
     * @param Event[]|array $events
     *
     * @return Tariff last current Tariff for private or shared private lessons
     */
    public function getCurrentPrivateLessonsTariffForEvents(array $events): Tariff
    {
        return $this->decidePrivateLessonsTariff($events) ? $this->tr->findCurrentPrivateLessonTariff() : $this->tr->findCurrentSharedPrivateLessonTariff();
    }

    public function getCalendarEventsListFromDates(\DateTimeInterface $start, \DateTimeInterface $end): ExportCalendarToList
    {
        $calendarEventsList = new ExportCalendarToList();
        do {
            $iteratedDate = clone $start;
            $events = $this->er->getEnabledFilteredByDateSortedByBeginAndClassroom($iteratedDate);
            $calendarEventsListDayItem = new ExportCalendarToListDayItem($iteratedDate->format('l'), $iteratedDate);
            $calendarEventsListDayItem->setEvents($events);
            if (count($events) > 0) {
                /** @var Event $iteratedEvent */
                $iteratedEvent = clone $events[0];
                $calendarEventsListDayHourItem = new ExportCalendarToListDayHourItem($iteratedEvent->getBegin()->format('H:i').' - '.$iteratedEvent->getEnd()->format('H:i'), $iteratedEvent->getBegin(), $iteratedEvent->getEnd());
                $calendarEventsListDayItem->addHour($calendarEventsListDayHourItem);
                /** @var Event $event */
                foreach ($events as $event) {
                    if ($event->getBeginString() === $iteratedEvent->getBeginString() && $event->getEndString() === $iteratedEvent->getEndString()) {
                        $calendarEventsListDayHourItem->addEvent($event);
                    } else {
                        $iteratedEvent = clone $event;
                        $calendarEventsListDayHourItem = new ExportCalendarToListDayHourItem($iteratedEvent->getBegin()->format('H:i').' - '.$iteratedEvent->getEnd()->format('H:i'), $iteratedEvent->getBegin(), $iteratedEvent->getEnd());
                        $calendarEventsListDayHourItem->addEvent($iteratedEvent);
                        $calendarEventsListDayItem->addHour($calendarEventsListDayHourItem);
                    }
                }
            }
            $calendarEventsList->addDay($calendarEventsListDayItem);
            // iterate $start date one day
            $start->add(new \DateInterval('P1D'));
        } while ($start->format(AbstractBase::DATABASE_DATE_STRING_FORMAT) < $end->format(AbstractBase::DATABASE_DATE_STRING_FORMAT));

        return $calendarEventsList;
    }

    public function getInvolvedUniqueStudentsInsideEventsList(array $eventsList): array
    {
        $result = [];
        /** @var Event $event */
        foreach ($eventsList as $event) {
            $students = $event->getStudents();
            /** @var Student $student */
            foreach ($students as $student) {
                if ($student->getMainEmailSubject()) {
                    $result[$student->getId()] = $student;
                }
            }
        }
        usort($result, static function (Student $a, Student $b) {
            if ($a->getFullCanonicalName() > $b->getFullCanonicalName()) {
                return 1;
            }

            if ($a->getFullCanonicalName() < $b->getFullCanonicalName()) {
                return -1;
            }

            return 0;
        });

        return $result;
    }
}
