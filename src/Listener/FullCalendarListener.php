<?php

namespace App\Listener;

use App\Entity\Event as AppEvent;
use App\Entity\Student;
use App\Entity\TeacherAbsence;
use App\Enum\UserRolesEnum;
use App\Form\Type\FilterCalendarEventsType;
use App\Form\Type\FilterStudentsMailingCalendarEventsType;
use App\Repository\EventRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherAbsenceRepository;
use App\Service\EventTransformerFactoryService;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class FullCalendarListener implements EventSubscriberInterface
{
    private EventRepository $ers;
    private TeacherAbsenceRepository $tars;
    private StudentRepository $srs;
    private EventTransformerFactoryService $etfs;
    private RequestStack $rss;
    private RouterInterface $router;
    private Security $security;

    public function __construct(EventRepository $ers, TeacherAbsenceRepository $tars, StudentRepository $srs, EventTransformerFactoryService $etfs, RequestStack $rss, RouterInterface $router, Security $security)
    {
        $this->ers = $ers;
        $this->tars = $tars;
        $this->srs = $srs;
        $this->etfs = $etfs;
        $this->rss = $rss;
        $this->router = $router;
        $this->security = $security;
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
        if ($this->rss->getCurrentRequest() && $this->rss->getCurrentRequest()->getBaseUrl()) {
            // probably dev environment
            $path = substr($referer, strpos($referer, $this->rss->getCurrentRequest()->getBaseUrl()));
            $path = str_replace($this->rss->getCurrentRequest()->getBaseUrl(), '', $path);
        } else {
            // prod environment
            $path = str_replace($this->rss->getCurrentRequest()->getSchemeAndHttpHost(), '', $referer);
        }
        $route = '';
        $parameters = [];
        $matcher = $this->router->getMatcher();
        if ($matcher) {
            $parameters = $matcher->match($path);
            $route = $parameters['_route'];
        }
        // // admin dashboard action
        if ('sonata_admin_dashboard' === $route) {
            $events = [];
            // classroom events
            if ($this->security->isGranted(UserRolesEnum::ROLE_ADMIN)) {
                // all teachers events
                if ($this->rss->getSession()->has(FilterCalendarEventsType::SESSION_KEY)) {
                    $events = $this->ers->getEnabledFilteredByBeginEndAndFilterCalendarEventForm($startDate, $endDate, $this->rss->getSession()->get(FilterCalendarEventsType::SESSION_KEY));
                } else {
                    $events = $this->ers->getEnabledFilteredByBeginAndEnd($startDate, $endDate);
                }
            } elseif ($this->security->isGranted(UserRolesEnum::ROLE_MANAGER)) {
                // only logged teacher events
                $events = $this->ers->getEnabledFilteredByTeacherBeginAndEnd($this->security->getUser()->getTeacher(), $startDate, $endDate);
            }
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

        // // admin student show action
        } elseif ('admin_app_student_show' === $route && array_key_exists('id', $parameters)) {
            // student events
            /** @var Student $student */
            $student = $this->srs->find((int) $parameters['id']);
            $events = $this->ers->getEnabledFilteredByBeginEndAndStudent($startDate, $endDate, $student);
            /** @var AppEvent $event */
            foreach ($events as $event) {
                $calendarEvent->addEvent($this->etfs->build($event));
            }

        // // admin student mailing action
        } elseif ('admin_app_student_mailing' === $route) {
            $events = [];
            // classroom events
            if ($this->security->isGranted(UserRolesEnum::ROLE_ADMIN)) {
                // all teachers events
                if ($this->rss->getSession()->has(FilterStudentsMailingCalendarEventsType::SESSION_KEY)) {
                    $events = $this->ers->getEnabledFilteredByBeginEndAndFilterCalendarEventForm($startDate, $endDate, $this->rss->getSession()->get(FilterStudentsMailingCalendarEventsType::SESSION_KEY));
                } else {
                    $events = $this->ers->getEnabledFilteredByBeginAndEnd($startDate, $endDate);
                }
                $this->rss->getSession()->set(FilterStudentsMailingCalendarEventsType::SESSION_KEY_FROM_DATE, $startDate);
                $this->rss->getSession()->set(FilterStudentsMailingCalendarEventsType::SESSION_KEY_TO_DATE, $endDate->sub(new \DateInterval('PT1H')));
            }
            /** @var AppEvent $event */
            foreach ($events as $event) {
                $calendarEvent->addEvent($this->etfs->build($event));
            }
        }
    }
}
