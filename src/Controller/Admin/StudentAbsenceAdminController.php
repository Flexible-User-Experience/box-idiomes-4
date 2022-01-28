<?php

namespace App\Controller\Admin;

use App\Entity\StudentAbsence;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class StudentAbsenceAdminController extends BaseAdminController
{
    public function notificationAction(): RedirectResponse
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());
        /** @var StudentAbsence $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $this->admin->checkAccess('show', $object);
        $object
            ->setHasBeenNotified(true)
            ->setNotificationDate(new \DateTime())
        ;
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $messenger = $this->container->get('app.notification');
        $messenger->sendStudentAbsenceNotification($object);
        $this->addFlash('success', 'S\'ha enviat un notificació per correu electrònic a l\'adreça '.$object->getStudent()->getMainEmailSubject().' advertint que l\'alumne '.$object->getStudent()->getFullName().' no ha assistit a la classe del dia '.$object->getDayString().'.');

        return $this->redirectToList();
    }
}
