<?php

namespace App\Controller\Admin;

use App\Entity\PreRegister;
use App\Entity\Student;
use DateTimeImmutable;
use Exception;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

final class PreRegisterAdminController extends BaseAdminController
{
    /**
     * Create new Student from PreRegister record action.
     */
    public function studentAction(): Response
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        /** @var PreRegister $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $object->setEnabled(true);
        $previouslyStoredStudents = $this->getDoctrine()->getRepository(Student::class)->getPreviouslyStoredStudentsFromPreRegister($object);
        if (count($previouslyStoredStudents) > 0) {
            // there are a previous Student with same name & surname
            $this->addFlash('warning', 'Ja existeix un alumne prèviament creat amb el mateix nom i cognoms. No s\'ha creat cap alumne nou.');
        } else {
            // brand new student
            $student = new Student();
            $student
                ->setName($object->getName())
                ->setSurname($object->getSurname())
                ->setPhone($object->getPhone())
                ->setEmail($object->getEmail())
                ->setComments($object->getComments())
                ->setBirthDate(new DateTimeImmutable())
            ;
            $this->getDoctrine()->getManager()->persist($student);
            $this->addFlash('success', 'S\'ha creat un nou alumne correctament.');
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToList();
    }

    public function batchActionGeneratestudents(ProxyQueryInterface $selectedModelQuery): RedirectResponse
    {
        $this->admin->checkAccess('show');
        $em = $this->getDoctrine()->getManager();
        $prrs = $this->getDoctrine()->getRepository(Student::class);
        $selectedModels = $selectedModelQuery->execute();
        $totalItemsIterated = 0;
        $newStudentsCreated = 0;
        try {
            /** @var PreRegister $selectedModel */
            foreach ($selectedModels as $selectedModel) {
                $previouslyStoredStudents = $prrs->getPreviouslyStoredStudentsFromPreRegister($selectedModel);
                if (0 === count($previouslyStoredStudents)) {
                    // brand new student
                    $student = new Student();
                    $student
                        ->setName($selectedModel->getName())
                        ->setSurname($selectedModel->getSurname())
                        ->setPhone($selectedModel->getPhone())
                        ->setEmail($selectedModel->getEmail())
                        ->setComments($selectedModel->getComments())
                        ->setBirthDate(new DateTimeImmutable())
                    ;
                    $em->persist($student);
                    ++$newStudentsCreated;
                }
                ++$totalItemsIterated;
            }
            $em->flush();
            if (0 === $newStudentsCreated) {
                $this->addFlash('warning', 'No s\'han creat cap alumne nou, totes les inscripcions seleccionades es corresponen amb alumnes existents.');
            } elseif ($newStudentsCreated < $totalItemsIterated) {
                $this->addFlash('warning', 'S\'han creat '.$newStudentsCreated.' alumnes nous, però '.($totalItemsIterated - $newStudentsCreated).' preinscripcions es corresponen amb alumnes ja existents.');
            } else {
                $this->addFlash('success', 'S\'han creat '.$newStudentsCreated.' alumnes correctament.');
            }
        } catch (Exception $e) {
            $this->addFlash('error', 'S\'ha produït un error inesperat al generar els alumnes seleccionats.');
            $this->addFlash('error', $e->getMessage());
        }

        return new RedirectResponse(
            $this->admin->generateUrl('list', [
                'filter' => $this->admin->getFilterParameters(),
            ])
        );
    }
}
