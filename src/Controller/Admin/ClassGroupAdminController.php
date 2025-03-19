<?php

namespace App\Controller\Admin;

use App\Entity\ClassGroup;
use App\Enum\UserRolesEnum;
use App\Pdf\ClassGroupBuilderPdf;
use App\Repository\StudentRepository;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ClassGroupAdminController extends CRUDController
{
    #[IsGranted(UserRolesEnum::ROLE_MANAGER)]
    public function emailsAction(Request $request, StudentRepository $srs, ClassGroupBuilderPdf $cgpbs, TranslatorInterface $translator, ParameterBagInterface $parameterBag): Response
    {
        $this->assertObjectExists($request, true);
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
        $pdf = $cgpbs->build($object, $students);

        return new Response($pdf->Output($parameterBag->get('project_export_filename').'_class_group_'.$object->getId().'.pdf'), 200, ['Content-type' => 'application/pdf']);
    }
}
