<?php

namespace App\Controller\Admin;

use App\Entity\ClassGroup;
use App\Pdf\ClassGroupBuilderPdf;
use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ClassGroupAdminController extends BaseAdminController
{
    /**
     * Get group emails list in PDF action.
     */
    public function emailsAction(Request $request): Response
    {
        /** @var StudentRepository $srs */
        $srs = $this->container->get('app.student_repository');
        /** @var TranslatorInterface $translator */
        $translator = $this->container->get('translator');
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());
        /** @var ClassGroup $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $students = $srs->getStudentsInClassGroupSortedByName($object);
        if (0 === count($students)) {
            $this->addFlash('warning', $translator->trans('backend.admin.class_group.emails_generator.flash_warning'));

            return $this->redirectToList();
        }
        /* @var ClassGroupBuilderPdf $cgpbs */
        $cgpbs = $this->container->get('app.class_group_pdf_builder');
        $pdf = $cgpbs->build($object, $students);

        return new Response($pdf->Output('pas_a_repas_class_group_'.$object->getId().'.pdf', 'I'), 200, ['Content-type' => 'application/pdf']);
    }
}
