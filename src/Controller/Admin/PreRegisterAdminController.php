<?php

namespace App\Controller\Admin;

use App\Entity\PreRegister;
use App\Entity\Student;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class PreRegisterAdminController.
 *
 * @category Controller
 */
class PreRegisterAdminController extends BaseAdminController
{
    /**
     * Create new Student from PreRegister record action.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedException If access is not granted
     */
    public function studentAction(Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var PreRegister $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        $object->setEnabled(true);
        // TODO manage name+surname unique exception
        $student = new Student();
        $student
            ->setName($object->getName())
            ->setSurname($object->getSurname())
            ->setPhone($object->getPhone())
            ->setEmail($object->getEmail())
            ->setComments($object->getComments())
            ->setBirthDate(new \DateTime())
        ;

        $this->getDoctrine()->getManager()->persist($student);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'S\'ha creat un nou alumne correctament.');

        return $this->redirectToList();
    }
}
