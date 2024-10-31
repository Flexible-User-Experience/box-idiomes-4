<?php

namespace App\Controller\Admin;

use App\Entity\Teacher;
use App\Repository\TeacherAbsenceRepository;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class TeacherAdminController extends CRUDController
{
    public function detailAction(Request $request, TeacherAbsenceRepository $tar): Response
    {
        $this->assertObjectExists($request, true);
        $id = $request->get($this->admin->getIdParameter());
        /** @var Teacher $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $absences = $tar->getTeacherAbsencesSortedByDate($object);

        return $this->render(
            'Admin/Teacher/detail.html.twig',
            [
                'action' => 'show',
                'object' => $object,
                'absences' => $absences,
                'elements' => $this->admin->getShow(),
            ]
        );
    }
}
