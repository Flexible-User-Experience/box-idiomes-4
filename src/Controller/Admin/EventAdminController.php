<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\EventStudent;
use App\Entity\Student;
use App\Entity\StudentAbsence;
use App\Form\Type\EventBatchRemoveType;
use App\Form\Type\EventType;
use App\Manager\EventManager;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

final class EventAdminController extends CRUDController
{
    public function editAction(Request $request): Response
    {
        $this->assertObjectExists($request, true);
        $id = $request->get($this->admin->getIdParameter());
        /** @var Event $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        if (!$object->getEnabled()) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        return parent::editAction($request);
    }

    /**
     * Edit event and all the next related events action.
     *
     * @throws Exception
     */
    public function batcheditAction(Request $request, EventManager $eventsManager, EntityManagerInterface $em): Response
    {
        $object = $this->getEvent($request);
        $firstEvent = $eventsManager->getFirstEventOf($object);
        if (is_null($firstEvent)) {
            $firstEvent = $object;
        }
        $lastEvent = $eventsManager->getLastEventOf($object);
        /** @var Form $form */
        $form = $this->createForm(EventType::class, $object, ['event' => $object]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eventIdStopRangeIterator = $form->get('range')->getData();
            $em->flush();
            $iteratorCounter = 1;
            if (!is_null($object->getNext())) {
                $iteratedEvent = $object;
                while (!is_null($iteratedEvent->getNext())) {
                    $currentBegin = $iteratedEvent->getBegin();
                    $currentEnd = $iteratedEvent->getEnd();
                    $currentBegin->add(new DateInterval('P'.$firstEvent->getDayFrequencyRepeat().'D'));
                    $currentEnd->add(new DateInterval('P'.$firstEvent->getDayFrequencyRepeat().'D'));
                    $iteratedEvent = $iteratedEvent->getNext();
                    if ($iteratedEvent->getId() <= $eventIdStopRangeIterator) {
                        $iteratedEvent
                            ->setBegin($currentBegin)
                            ->setEnd($currentEnd)
                            ->setTeacher($object->getTeacher())
                            ->setClassroom($object->getClassroom())
                            ->setGroup($object->getGroup())
                            ->setStudents($object->getStudents());
                        $em->flush();
                        ++$iteratorCounter;
                    }
                }
            }
            $this->addFlash(
                'success',
                'S\'han modificat '.$iteratorCounter.' esdeveniments del calendari d\'horaris correctament.'
            );

            return $this->redirectToList();
        }

        return $this->renderWithExtraParams(
            'Admin/Event/batch_edit_form.html.twig',
            [
                'action' => 'batchedit',
                'object' => $object,
                'firstEvent' => $firstEvent,
                'lastEvent' => $lastEvent,
                'progressBarPercentiles' => $eventsManager->getProgressBarPercentilesOf($object),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Delete event and all the next related events action.
     */
    public function batchdeleteAction(Request $request, EventManager $eventsManager, EntityManagerInterface $em): Response
    {
        $object = $this->getEvent($request);
        $firstEvent = $eventsManager->getFirstEventOf($object);
        $lastEvent = $eventsManager->getLastEventOf($object);
        /** @var Form $form */
        $form = $this->createForm(EventBatchRemoveType::class, $object, ['event' => $object]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $eventIdStopRange = $form->get('range')->getData();
            /** @var Event|null $eventStopRange */
            $eventStopRange = $em->getRepository(Event::class)->find($eventIdStopRange);
            /** @var Event|null $eventAfterStopRange */
            $eventAfterStopRange = null;
            if ($eventStopRange && !is_null($eventStopRange->getNext())) {
                $eventAfterStopRange = $em->getRepository(Event::class)->find($eventStopRange->getNext()->getId());
            }
            /** @var Event|null $eventBeforeStartRange */
            $eventBeforeStartRange = null;
            if (!is_null($object->getPrevious())) {
                $eventBeforeStartRange = $em->getRepository(Event::class)->find($object->getPrevious()->getId());
            }
            // begin range
            if (is_null($firstEvent)) {
                $iteratorCounter = 1;
                if (!is_null($object->getNext())) {
                    $iteratedEvent = $object;
                    while (!is_null($iteratedEvent->getNext())) {
                        $iteratedEvent = $iteratedEvent->getNext();
                        if ($iteratedEvent->getId() <= $eventIdStopRange) {
                            $iteratedEvent->setEnabled(false);
                            ++$iteratorCounter;
                        }
                    }
                    if (!is_null($eventAfterStopRange)) {
                        $eventAfterStopRange->setPrevious(null);
                    }
                }
                $object->setEnabled(false);
                $em->flush();
            // end range
            } elseif (is_null($eventAfterStopRange)) {
                $iteratorCounter = 1;
                $iteratedEvent = $object;
                while (!is_null($iteratedEvent->getNext())) {
                    $iteratedEvent = $iteratedEvent->getNext();
                    if ($iteratedEvent->getId() <= $eventIdStopRange) {
                        $iteratedEvent->setEnabled(false);
                        ++$iteratorCounter;
                    }
                }
                $object->setEnabled(false);
                if (!is_null($eventBeforeStartRange)) {
                    $eventBeforeStartRange->setNext(null);
                }
                $em->flush();
            // middle range
            } else {
                if (is_null($eventBeforeStartRange)) {
                    $eventBeforeStartRange = $firstEvent;
                }
                if (is_null($eventAfterStopRange)) {
                    $eventAfterStopRange = $lastEvent;
                }
                $eventBeforeStartRange->setNext($eventAfterStopRange);
                $eventAfterStopRange->setPrevious($eventBeforeStartRange);
                $em->flush();
                $iteratorCounter = 1;
                if (!is_null($object->getNext())) {
                    $iteratedEvent = $object;
                    while (!is_null($iteratedEvent->getNext())) {
                        $iteratedEvent = $iteratedEvent->getNext();
                        if ($iteratedEvent->getId() <= $eventIdStopRange) {
                            $iteratedEvent->setEnabled(false);
                            ++$iteratorCounter;
                        }
                    }
                    $object->setEnabled(false);
                    $em->flush();
                }
            }

            $this->addFlash(
                'success',
                'S\'han esborrat '.$iteratorCounter.' esdeveniments del calendari d\'horaris correctament.'
            );

            return $this->redirectToList();
        }

        return $this->renderWithExtraParams(
            'Admin/Event/batch_delete_form.html.twig',
            [
                'action' => 'batchdelete',
                'object' => $object,
                'firstEvent' => $firstEvent,
                'lastEvent' => $lastEvent,
                'progressBarPercentiles' => $eventsManager->getProgressBarPercentilesOf($object),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * API GET action.
     */
    public function apigetAction(Request $request, EngineInterface $twig, EntityManagerInterface $em): JsonResponse
    {
        $id = $request->get($this->admin->getIdParameter());
        /** @var Event $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        if (!$object->getEnabled()) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        // init synchro process, create new references
        $items = $em->getRepository(EventStudent::class)->getItemsByEvent($object);
        $resultItems = [];
        /** @var Student $student */
        foreach ($object->getStudents() as $student) {
            $alreadyExists = false;
            /** @var EventStudent $item */
            foreach ($items as $item) {
                if ($item->getStudent()->getId() === $student->getId()) {
                    $alreadyExists = true;
                    break;
                }
            }
            if (!$alreadyExists) {
                $newEventStudent = new EventStudent();
                $newEventStudent
                    ->setEvent($object)
                    ->setStudent($student)
                    ->setHasAttendedTheClass(true)
                ;
                $em->persist($newEventStudent);
                $resultItems[] = $newEventStudent;
            } else {
                $resultItems[] = $item;
            }
        }
        $em->flush();
        $resonse = [
            'eid' => $object->getId(),
            'html' => $twig->render('Admin/Event/api_get.html.twig', [
                'items' => $resultItems,
            ]),
        ];

        return new JsonResponse($resonse);
    }

    /**
     * API attended action.
     */
    public function apiattendedclassAction(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $event = $this->getEvent($request);
        $student = $this->getStudent($request);
        $searchedStudentAbsence = $this->getStudentAbsenceByEventAndStudent($event, $student);
        if ($searchedStudentAbsence) {
            $em->remove($searchedStudentAbsence);
            $em->flush();
        }

        return $this->commonAttendedClass($event, $student, true);
    }

    /**
     * API not attended action.
     */
    public function apinotattendedclassAction(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $event = $this->getEvent($request);
        $student = $this->getStudent($request);
        $searchedStudentAbsence = $this->getStudentAbsenceByEventAndStudent($event, $student);
        if (!$searchedStudentAbsence) {
            $studentAbsence = new StudentAbsence();
            $studentAbsence
                ->setDay($event->getBegin())
                ->setStudent($student)
            ;
            $em->persist($studentAbsence);
            $em->flush();

            return $this->commonAttendedClass($event, $student, false, $studentAbsence->getId());
        }

        return $this->commonAttendedClass($event, $student, false);
    }

    public function commonAttendedClass(Event $event, Student $student, bool $attended, ?int $studentAbsenceId = null): JsonResponse
    {
        $searchedEventStudent = $this->getDoctrine()->getRepository(EventStudent::class)->findOneBy([
            'event' => $event,
            'student' => $student,
        ]);
        if (!$searchedEventStudent) {
            $searchedEventStudent = new EventStudent();
            $searchedEventStudent
                ->setEvent($event)
                ->setStudent($student)
            ;
            $this->getDoctrine()->getManager()->persist($searchedEventStudent);
        }
        $searchedEventStudent->setHasAttendedTheClass($attended);
        $this->getDoctrine()->getManager()->flush();
        if ($studentAbsenceId) {
            $resonse = [
                'eid' => $event->getId(),
                'student' => $student->getId(),
                'said' => $studentAbsenceId,
            ];
        } else {
            $resonse = [
                'eid' => $event->getId(),
                'student' => $student->getId(),
            ];
        }

        return new JsonResponse($resonse);
    }

    private function getEvent(Request $request): Event
    {
        $id = $request->get($this->admin->getIdParameter());
        /** @var Event $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the event with id: %s', $id));
        }
        if (!$object->getEnabled()) {
            throw $this->createNotFoundException(sprintf('unable to find the event with id: %s', $id));
        }

        return $object;
    }

    private function getStudent(Request $request): Student
    {
        $sid = $request->get('student');
        $student = $this->getDoctrine()->getRepository(Student::class)->find((int) $sid);
        if (!$student) {
            throw $this->createNotFoundException(sprintf('unable to find the student with id: %s', $sid));
        }

        return $student;
    }

    private function getStudentAbsenceByEventAndStudent(Event $event, Student $student): ?StudentAbsence
    {
        return $this->getDoctrine()->getRepository(StudentAbsence::class)->findOneBy([
            'day' => $event->getBegin(),
            'student' => $student,
        ]);
    }
}
