<?php

namespace App\Controller\Admin;

use App\Entity\StudentAbsence;
use App\Enum\UserRolesEnum;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class StudentAbsenceAdminController extends CRUDController
{
    #[IsGranted(UserRolesEnum::ROLE_MANAGER)]
    public function notificationAction(Request $request, EntityManagerInterface $em, NotificationService $messenger): RedirectResponse
    {
        $this->assertObjectExists($request, true);
        $id = $request->get($this->admin->getIdParameter());
        /** @var StudentAbsence $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $this->admin->checkAccess('show', $object);
        $object
            ->setHasBeenNotified(true)
            ->setNotificationDate(new \DateTimeImmutable())
        ;
        $em->flush();
        $messenger->sendStudentAbsenceNotification($object);
        $this->addFlash('success', 'S\'ha enviat un notificació per correu electrònic a l\'adreça '.$object->getStudent()->getMainEmailSubject().' advertint que l\'alumne '.$object->getStudent()->getFullName().' no ha assistit a la classe del dia '.$object->getDayString().'.');

        return $this->redirectToList();
    }
}
