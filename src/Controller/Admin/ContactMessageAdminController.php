<?php

namespace App\Controller\Admin;

use App\Entity\ContactMessage;
use App\Form\Type\ContactMessageAnswerType;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ContactMessageAdminController extends CRUDController
{
    public function showAction(Request $request): Response
    {
        $this->assertObjectExists($request, true);
        $id = $request->get($this->admin->getIdParameter());
        /** @var ContactMessage $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $object->setChecked(true);
        $this->admin->checkAccess('show', $object);
        $preResponse = $this->preShow($request, $object);
        if (null !== $preResponse) {
            return $preResponse;
        }
        $this->admin->setSubject($object);
        $em = $this->getDoctrine()->getManager();
        $em->persist($object);
        $em->flush();

        return $this->renderWithExtraParams(
            $this->admin->getTemplate('show'),
            [
                'action' => 'show',
                'object' => $object,
                'elements' => $this->admin->getShow(),
            ]
        );
    }

    /**
     * Answer message action.
     */
    public function answerAction(Request $request, EntityManagerInterface $em, NotificationService $messenger): Response
    {
        $this->assertObjectExists($request, true);
        $id = $request->get($this->admin->getIdParameter());
        /** @var ContactMessage $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $form = $this->createForm(ContactMessageAnswerType::class, $object);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // persist new contact message form record
            $object->setChecked(true);
            $object->setAnswered(true);
            $em->persist($object);
            $em->flush();
            // send notifications
            $messenger->sendUserBackendNotification($object);
            // build flash message
            $this->addFlash('success', 'Your answer has been sent.');

            return $this->redirectToRoute('admin_app_contactmessage_list');
        }

        return $this->renderWithExtraParams(
            'Admin/ContactMessage/answer_form.html.twig',
            [
                'action' => 'answer',
                'object' => $object,
                'form' => $form->createView(),
                'elements' => $this->admin->getShow(),
            ]
        );
    }
}
