<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Entity\MailingStudentsNotificationMessage;
use App\Entity\NewsletterContact;
use App\Form\Type\ContactMessageType;
use App\Kernel;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function indexAction(): RedirectResponse
    {
        return $this->redirectToRoute('admin_app_login');
    }

    private function setFlashMessageAndEmailNotifications(NotificationService $messenger, NewsletterContact $newsletterContact): void
    {
        // Send email notifications
        if (0 !== $messenger->sendCommonNewsletterUserNotification($newsletterContact)) {
            // Set frontend flash message
            $this->addFlash(
                'notice',
                'Gràcies per subscriure\'t al newsletter'
            );
        } else {
            $this->addFlash(
                'danger',
                'El teu missatge no s\'ha enviat'
            );
        }
        $messenger->sendNewsletterSubscriptionAdminNotification($newsletterContact);
    }

    #[Route('/contacte-iframe', name: 'app_contact_embed')]
    public function contactEmbedAction(Request $request, EntityManagerInterface $em, NotificationService $messenger): Response
    {
        $contactMessage = new ContactMessage();
        $contactMessageForm = $this->createForm(ContactMessageType::class, $contactMessage);
        $contactMessageForm->handleRequest($request);
        if ($contactMessageForm->isSubmitted() && $contactMessageForm->isValid()) {
            // Persist new contact message into DB
            $em->persist($contactMessage);
            $em->flush();
            // Send email notifications
            if (0 !== $messenger->sendCommonUserNotification($contactMessage)) {
                // Set frontend flash message
                $this->addFlash(
                    'notice',
                    'El teu missatge s\'ha enviat correctament'
                );
            } else {
                $this->addFlash(
                    'danger',
                    'El teu missatge no s\'ha enviat'
                );
            }
            $messenger->sendContactAdminNotification($contactMessage);
            // Clean up new form
            $contactMessage = new ContactMessage();
            $contactMessageForm = $this->createForm(ContactMessageType::class, $contactMessage);
        }

        return $this->render(
            'Front/contact_embed.html.twig',
            [
                'contactMessageForm' => $contactMessageForm,
            ]
        );
    }

    #[Route('/test-email', name: 'app_test_email')]
    public function testEmailAction(KernelInterface $kernel, EntityManagerInterface $em): Response
    {
        if (Kernel::ENV_PROD === $kernel->getEnvironment()) {
            throw new AccessDeniedHttpException();
        }
        $notification = $em->getRepository(MailingStudentsNotificationMessage::class)->find(1);

        return $this->render(
            'Mails/mailing_notification.html.twig',
            [
                'notification' => $notification,
            ]
        );
    }
}
