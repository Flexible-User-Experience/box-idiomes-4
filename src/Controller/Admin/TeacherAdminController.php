<?php

namespace App\Controller\Admin;

use App\Entity\Teacher;
use Symfony\Component\HttpFoundation\Response;

final class TeacherAdminController extends BaseAdminController
{
    public function detailAction(): Response
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        /** @var Teacher $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $absences = $this->container->get('app.teacher_absence_repository')->getTeacherAbsencesSortedByDate($object);

        return $this->renderWithExtraParams(
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
