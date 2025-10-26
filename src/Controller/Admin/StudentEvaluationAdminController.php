<?php

namespace App\Controller\Admin;

use App\Entity\StudentEvaluation;
use App\Enum\StudentEvaluationEnum;
use App\Enum\UserRolesEnum;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

final class StudentEvaluationAdminController extends CRUDController
{
    #[IsGranted(UserRolesEnum::ROLE_MANAGER)]
    public function notificationAction(Request $request, EntityManagerInterface $em, NotificationService $messenger, TranslatorInterface $translator): RedirectResponse
    {
        $this->assertObjectExists($request, true);
        $id = $request->get($this->admin->getIdParameter());
        /** @var StudentEvaluation $object */
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
        //        $messenger->sendStudentAbsenceNotification($object); // TODO render PDF & send by email
        $this->addFlash(
            'success',
            sprintf(
                'S\'ha enviat un correu electrònic a l\'adreça %s amb l\'avaluació %s del %s de l\'alumne %s.',
                $object->getStudent()->getMainEmailSubject(),
                $object->getFullCourseAsString(),
                $translator->trans(StudentEvaluationEnum::getReversedEnumArray()[$object->getEvaluation()]),
                $object->getStudent()->getFullName()
            )
        );

        return $this->redirectToList();
    }
}
