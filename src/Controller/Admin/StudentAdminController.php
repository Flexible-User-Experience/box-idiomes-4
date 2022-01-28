<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use App\Pdf\SepaAgreementBuilderPdf;
use App\Pdf\StudentImageRightsBuilderPdf;
use Symfony\Component\HttpFoundation\Response;

final class StudentAdminController extends BaseAdminController
{
    public function imagerightsAction(): Response
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        /** @var Student $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        /** @var StudentImageRightsBuilderPdf $sirps */
        $sirps = $this->get('app.student_image_rights_pdf_builder');
        $pdf = $sirps->build($object);

        return new Response($pdf->Output('student_image_rights_'.$object->getId().'.pdf', 'I'), 200, ['Content-type' => 'application/pdf']);
    }

    public function sepaagreementAction(): Response
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        /** @var Student $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        /** @var SepaAgreementBuilderPdf $saps */
        $saps = $this->get('app.sepa_agreement_pdf_builder');
        $pdf = $saps->build($object);

        return new Response($pdf->Output('sepa_agreement_'.$object->getId().'.pdf', 'I'), 200, ['Content-type' => 'application/pdf']);
    }

    public function showAction($deprecatedId = null): Response
    {
        $request = $this->getRequest();
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
}
