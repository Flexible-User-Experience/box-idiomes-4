<?php

namespace App\Controller\Admin;

use App\Entity\ClassGroup;
use App\Entity\MailingStudentsNotificationMessage;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\TrainingCenter;
use App\Form\Model\FilterCalendarEventModel;
use App\Form\Type\FilterStudentsMailingCalendarEventsType;
use App\Form\Type\MailingStudentsNotificationMessageType;
use App\Manager\EventManager;
use App\Message\NewMailingStudentsNotificationMessage;
use App\Pdf\SepaAgreementBuilderPdf;
use App\Pdf\StudentImageRightsBuilderPdf;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

final class StudentAdminController extends CRUDController
{
    public function imagerightsAction(Request $request, StudentImageRightsBuilderPdf $sirps): Response
    {
        $this->assertObjectExists($request, true);
        $id = $request->get($this->admin->getIdParameter());
        /** @var Student $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $pdf = $sirps->build($object);

        return new Response($pdf->Output('student_image_rights_'.$object->getId().'.pdf'), 200, ['Content-type' => 'application/pdf']);
    }

    public function sepaagreementAction(Request $request, SepaAgreementBuilderPdf $saps): Response
    {
        $this->assertObjectExists($request, true);
        $id = $request->get($this->admin->getIdParameter());
        /** @var Student $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $pdf = $saps->build($object);

        return new Response($pdf->Output('sepa_agreement_'.$object->getId().'.pdf'), 200, ['Content-type' => 'application/pdf']);
    }

    public function showAction(Request $request): Response
    {
        $this->assertObjectExists($request, true);
        $id = $request->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $this->admin->checkAccess('show', $object);
        $this->admin->setSubject($object);

        return $this->renderWithExtraParams(
            'Admin/Student/show.html.twig',
            [
                'action' => 'show',
                'object' => $object,
                'elements' => $this->admin->getShow(),
            ]
        );
    }

    public function mailingAction(Request $request): Response
    {
        $calendarEventsFilter = new FilterCalendarEventModel();
        if ($request->getSession()->has(FilterStudentsMailingCalendarEventsType::SESSION_KEY)) {
            $calendarEventsFilter = $request->getSession()->get(FilterStudentsMailingCalendarEventsType::SESSION_KEY);
        }
        $calendarEventsFilterForm = $this->createForm(FilterStudentsMailingCalendarEventsType::class, $calendarEventsFilter);
        $calendarEventsFilterForm->handleRequest($request);
        if ($calendarEventsFilterForm->isSubmitted()) {
            $request->getSession()->set(FilterStudentsMailingCalendarEventsType::SESSION_KEY, $calendarEventsFilter);
        }

        return $this->renderWithExtraParams(
            'Admin/Student/mailing.html.twig',
            [
                'filter' => $calendarEventsFilterForm->createView(),
            ]
        );
    }

    public function mailingResetAction(Request $request): Response
    {
        $request->getSession()->remove(FilterStudentsMailingCalendarEventsType::SESSION_KEY);
        $request->getSession()->remove(FilterStudentsMailingCalendarEventsType::SESSION_KEY_FROM_DATE);
        $request->getSession()->remove(FilterStudentsMailingCalendarEventsType::SESSION_KEY_TO_DATE);

        return $this->redirectToRoute('admin_app_student_mailing');
    }

    public function writeMailingAction(Request $request, EntityManagerInterface $entityManager, EventRepository $ers, EventManager $em, MessageBusInterface $bus): Response
    {
        $startDate = $request->getSession()->get(FilterStudentsMailingCalendarEventsType::SESSION_KEY_FROM_DATE);
        $endDate = $request->getSession()->get(FilterStudentsMailingCalendarEventsType::SESSION_KEY_TO_DATE);
        $calendarEventsFilter = new FilterCalendarEventModel();
        if ($request->getSession()->has(FilterStudentsMailingCalendarEventsType::SESSION_KEY)) {
            $calendarEventsFilter = $request->getSession()->get(FilterStudentsMailingCalendarEventsType::SESSION_KEY);
            $events = $ers->getEnabledFilteredByBeginEndAndFilterCalendarEventForm($startDate, $endDate, $request->getSession()->get(FilterStudentsMailingCalendarEventsType::SESSION_KEY));
        } else {
            $events = $ers->getEnabledFilteredByBeginAndEnd($startDate, $endDate);
        }
        $students = $em->getInvolvedUniqueStudentsInsideEventsList($events);
        $mailingStudentsNotificationMessage = new MailingStudentsNotificationMessage();
        $form = $this->createForm(MailingStudentsNotificationMessageType::class, $mailingStudentsNotificationMessage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mailingStudentsNotificationMessage
                ->setSendDate(new \DateTimeImmutable())
                ->setIsSended(true)
                ->setFilterStartDate($startDate)
                ->setFilterEndDate($endDate)
                ->setFilteredClassroom($calendarEventsFilter->getClassroom())
                ->setTotalTargetStudents(count($students))
            ;
            if ($calendarEventsFilter->getTeacher()) {
                $filteredTeacher = $entityManager->getRepository(Teacher::class)->find($calendarEventsFilter->getTeacher()->getId());
                $mailingStudentsNotificationMessage->setFilteredTeacher($filteredTeacher);
            }
            if ($calendarEventsFilter->getGroup()) {
                $filteredClassGroup = $entityManager->getRepository(ClassGroup::class)->find($calendarEventsFilter->getGroup()->getId());
                $mailingStudentsNotificationMessage->setFilteredClassGroup($filteredClassGroup);
            }
            if ($calendarEventsFilter->getTrainingCenter()) {
                $filteredTrainingCenter = $entityManager->getRepository(TrainingCenter::class)->find($calendarEventsFilter->getTrainingCenter()->getId());
                $mailingStudentsNotificationMessage->setFilteredTrainingCenter($filteredTrainingCenter);
            }
            $entityManager->persist($mailingStudentsNotificationMessage);
            $entityManager->flush();
            /** @var Student $student */
            foreach ($students as $student) {
                $bus->dispatch(new NewMailingStudentsNotificationMessage($student->getId(), $mailingStudentsNotificationMessage->getId()));
            }

            return $this->redirectToRoute('admin_app_student_deliver_massive_mailing');
        }

        return $this->renderWithExtraParams(
            'Admin/Student/write_mailing.html.twig',
            [
                'form' => $form->createView(),
                'calendar_events_filter' => $calendarEventsFilter,
                'students' => $students,
            ]
        );
    }

    public function deliverMassiveMailingAction(Request $request): Response
    {
        $request->getSession()->remove(FilterStudentsMailingCalendarEventsType::SESSION_KEY);
        $request->getSession()->remove(FilterStudentsMailingCalendarEventsType::SESSION_KEY_FROM_DATE);
        $request->getSession()->remove(FilterStudentsMailingCalendarEventsType::SESSION_KEY_TO_DATE);

        return $this->renderWithExtraParams(
            'Admin/Student/deliver_massive_mailing.html.twig',
            [
            ]
        );
    }
}
