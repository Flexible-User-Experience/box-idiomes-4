<?php

namespace App\Listener;

use App\Entity\Event as AppEvent;
use App\Entity\Student;
use App\Entity\TeacherAbsence;
use App\Repository\EventRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherAbsenceRepository;
use App\Service\EventTrasnformerFactoryService;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class FullCalendarListener.
 *
 * @category Listener
 */
class FullCalendarListener implements EventSubscriberInterface
{
    /**
     * @var EventRepository
     */
    private $ers;

    /**
     * @var TeacherAbsenceRepository
     */
    private $tars;

    /**
     * @var StudentRepository
     */
    private $srs;

    /**
     * @var EventTrasnformerFactoryService
     */
    private $etfs;

    /**
     * @var RequestStack
     */
    private $rss;

    /**
     * @var Router
     */
    private $router;

    /**
     * Methods.
     */

    /**
     * FullcalendarListener constructor.
     */
    public function __construct(EventRepository $ers, TeacherAbsenceRepository $tars, StudentRepository $srs, EventTrasnformerFactoryService $etfs, RequestStack $rss, RouterInterface $router)
    {
        $this->ers = $ers;
        $this->tars = $tars;
        $this->srs = $srs;
        $this->etfs = $etfs;
        $this->rss = $rss;
        $this->router = $router;
    }

    /**
     * @return array|string[]
     */
    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'loadData',
        ];
    }

    public function loadData(CalendarEvent $calendarEvent)
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

        if ('sonata_admin_dashboard' == $route) {
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
        } elseif ('admin_app_student_show' == $route) {
            //// admin student show action
            // student events
            /** @var Student $student */
            $student = $this->srs->find(intval($parameters['id']));
            $events = $this->ers->getEnabledFilteredByBeginEndAndStudent($startDate, $endDate, $student);
            /** @var AppEvent $event */
            foreach ($events as $event) {
                $calendarEvent->addEvent($this->etfs->build($event));
            }
        }
    }
}
