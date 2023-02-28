<?php

namespace App\Controller\Admin;

use App\Entity\ContactMessage;
use App\Entity\Student;
use App\Form\Model\FilterCalendarEventModel;
use App\Form\Type\FilterStudentsMailingCalendarEventsType;
use App\Form\Type\MailingStudentsNotificationMessageType;
use App\Pdf\SepaAgreementBuilderPdf;
use App\Pdf\StudentImageRightsBuilderPdf;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
                'selected_class_groups' => $request->getSession()->has(FilterStudentsMailingCalendarEventsType::SELECTED_CLASS_GROUPS_SESSION_KEY) ? $request->getSession()->get(FilterStudentsMailingCalendarEventsType::SELECTED_CLASS_GROUPS_SESSION_KEY) : 0,
            ]
        );
    }

    public function mailingResetAction(Request $request): Response
    {
        $request->getSession()->remove(FilterStudentsMailingCalendarEventsType::SESSION_KEY);

        return $this->redirectToRoute('admin_app_student_mailing');
    }

    public function writeMailingAction(Request $request): Response
    {
        $calendarEventsFilter = new FilterCalendarEventModel();
        if ($request->getSession()->has(FilterStudentsMailingCalendarEventsType::SESSION_KEY)) {
            $calendarEventsFilter = $request->getSession()->get(FilterStudentsMailingCalendarEventsType::SESSION_KEY);
        }
        $form = $this->createForm(MailingStudentsNotificationMessageType::class, new ContactMessage());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('admin_app_contactmessage_list');
        }

        return $this->renderWithExtraParams(
            'Admin/Student/write_mailing.html.twig',
            [
                'form' => $form->createView(),
                'calendar_events_filter' => $calendarEventsFilter,
                'selected_class_groups' => $request->getSession()->has(FilterStudentsMailingCalendarEventsType::SESSION_KEY) ? 1 : 0,
            ]
        );
    }
}
