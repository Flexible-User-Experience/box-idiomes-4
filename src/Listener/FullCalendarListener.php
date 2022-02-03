<?php

namespace App\Listener;

use App\Entity\Event as AppEvent;
use App\Entity\Student;
use App\Entity\TeacherAbsence;
use App\Repository\EventRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherAbsenceRepository;
use App\Service\EventTransformerFactoryService;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class FullCalendarListener implements EventSubscriberInterface
{
    private EventRepository $ers;
    private TeacherAbsenceRepository $tars;
    private StudentRepository $srs;
    private EventTransformerFactoryService $etfs;
    private RequestStack $rss;
    private RouterInterface $router;

    public function __construct(EventRepository $ers, TeacherAbsenceRepository $tars, StudentRepository $srs, EventTransformerFactoryService $etfs, RequestStack $rss, RouterInterface $router)
    {
        $this->ers = $ers;
        $this->tars = $tars;
        $this->srs = $srs;
        $this->etfs = $etfs;
        $this->rss = $rss;
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CalendarEvents::SET_DATA => 'loadData',
        ];
    }

    public function loadData(CalendarEvent $calendarEvent): void
    {
        $startDate = $calendarEvent->getStart();
        $endDate = $calendarEvent->getEnd();
        $referer = $this->rss->getCurrentRequest()->headers->get('referer');
        if ($this->rss->getCurrentRequest()->getBaseUrl()) {
            // probably dev environment
            $path = substr($referer, strpos($referer, $this->rss->getCurrentRequest()->getBaseUrl()));
            $path = str_replace($this->rss->getCurrentRequest()->getBaseUrl(), '', $path);
        } else {
            // prod environment
            $path = str_replace($this->rss->getCurrentRequest()->getSchemeAndHttpHost(), '', $referer);
        }

        $matcher = $this->router->getMatcher();
        $parameters = $matcher->match($path);
        $route = $parameters['_route'];

        if ('sonata_admin_dashboard' === $route) {
            //// admin dashboard action
            // classroom events
            $events = $this->ers->getEnabledFilteredByBeginAndEnd($startDate, $endDate);
            /** @var AppEvent $event */
            foreach ($events as $event) {
                $calendarEvent->addEvent($this->etfs->build($event));
            }
            // teacher absences
            $events = $this->tars->getFilteredByBeginAndEnd($startDate, $endDate);
            /** @var TeacherAbsence $event */
            foreach ($events as $event) {
                $calendarEvent->addEvent($this->etfs->buildTeacherAbsence($event));
            }
        } elseif ('admin_app_student_show' === $route) {
            //// admin student show action
            // student events
            /** @var Student $student */
            $student = $this->srs->find((int) $parameters['id']);
            $events = $this->ers->getEnabledFilteredByBeginEndAndStudent($startDate, $endDate, $student);
            /** @var AppEvent $event */
            foreach ($events as $event) {
                $calendarEvent->addEvent($this->etfs->build($event));
            }
        }
    }
}
