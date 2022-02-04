<?php

namespace App\Controller;

use App\Entity\ContactMessage;
use App\Entity\Invoice;
use App\Entity\NewsletterContact;
use App\Entity\PreRegister;
use App\Entity\Service;
use App\Entity\Teacher;
use App\Form\Type\ContactHomepageType;
use App\Form\Type\ContactMessageType;
use App\Form\Type\PreRegisterType;
use App\Kernel;
use App\Manager\MailchimpManager;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function indexAction(Request $request, EntityManagerInterface $em, MailchimpManager $mailchimpManager, NotificationService $messenger): Response
    {
        $teachers = $em->getRepository(Teacher::class)->findAllEnabledSortedByPosition();
        $contact = new NewsletterContact();
        $newsletterForm = $this->createForm(ContactHomepageType::class, $contact);
        $newsletterForm->handleRequest($request);
        if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
            // Persist new contact message into DB
            $em->persist($contact);
            $em->flush();
            // Subscribe contact to mailchimp list
            $result = $mailchimpManager->subscribeContactToList($contact, $this->getParameter('mailchimp_list_id'));
            if (is_array($result) && MailchimpManager::SUBSCRIBED === $result['status']) {
                // Send notification and OK flash
                $this->setFlashMessageAndEmailNotifications($messenger, $contact);
                // Clean up new form
                $contact = new NewsletterContact();
                $newsletterForm = $this->createForm(ContactHomepageType::class, $contact);
            } else {
                // Mailchimp error
                $this->addFlash(
                    'danger',
                    'S\'ha produït un error durant el procés de registre al newsletter. Torna a provar-ho més tard o contacta a través del formulari web.'
                );
            }
        }

        return $this->render(
            'Front/homepage.html.twig',
            [
                'teachers' => $teachers,
                'newsletterForm' => $newsletterForm->createView(),
            ]
        );
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

    /**
     * @Route("/serveis", name="app_services")
     */
    public function servicesAction(EntityManagerInterface $em): Response
    {
        return $this->render(
            'Front/services.html.twig',
            [
                'services' => $em->getRepository(Service::class)->findAllEnabledSortedByPosition(),
            ]
        );
    }

    /**
     * @Route("/academia", name="app_academy")
     */
    public function academyAction(): Response
    {
        return $this->render('Front/academy.html.twig');
    }

    /**
     * @Route("/contacte", name="app_contact")
     */
    public function contactAction(Request $request, EntityManagerInterface $em, NotificationService $messenger): Response
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
            'Front/contact.html.twig',
            [
                'contactMessageForm' => $contactMessageForm->createView(),
            ]
        );
    }

    /**
     * @Route("/preinscripcions", name="app_pre_register")
     */
    public function preRegistersAction(Request $request, EntityManagerInterface $em, NotificationService $messenger): Response
    {
//        $this->addFlash(
//            'notice',
//            'Redirect flash.'
//        );
//
//        return $this->redirectToRoute('app_homepage');
        $preRegister = new PreRegister();
        $preRegisterForm = $this->createForm(PreRegisterType::class, $preRegister);
        $preRegisterForm->handleRequest($request);
        if ($preRegisterForm->isSubmitted() && $preRegisterForm->isValid()) {
            // Persist new pre-register record into DB
            $preRegister->setEnabled(false);
            $em->persist($preRegister);
            $em->flush();
            if (0 !== $messenger->sendPreRegisterAdminNotification($preRegister)) {
                // Set frontend flash message
                $this->addFlash(
                    'notice',
                    'La teva preinscripció s\'ha enviat correctament. Ens posarem en contacte amb tu tan aviat com ens sigui possible.'
                );
            } else {
                $this->addFlash(
                    'danger',
                    'S\'ha produït un error inesperat durant el registre de la teva preinscripció. Si us plau, contacta directament amb nosaltres a través del telèfon que apareix al peu d\'aquesta pàgina. Gràcies.'
                );
            }
            // Clean up new form
            $preRegister = new PreRegister();
            $preRegisterForm = $this->createForm(PreRegisterType::class, $preRegister);
        }

        return $this->render(
            'Front/pre_register.html.twig',
            [
                'preRegisterForm' => $preRegisterForm->createView(),
            ]
        );
    }

    /**
     * @Route("/politica-de-privacitat", name="app_privacy_policy")
     */
    public function privacyPolicyAction(): Response
    {
        return $this->render('Front/privacy_policy.html.twig');
    }

    /**
     * @Route("/credits", name="app_credits")
     */
    public function creditsAction(): Response
    {
        return $this->render('Front/credits.html.twig');
    }

    /**
     * @Route("/test-email", name="app_test_email")
     */
    public function testEmailAction(KernelInterface $kernel, EntityManagerInterface $em): Response
    {
        if (Kernel::ENV_PROD === $kernel->getEnvironment()) {
            throw new AccessDeniedHttpException();
        }

        return $this->render(
            'Mails/invoice_pdf_notification.html.twig',
            [
                'invoice' => $em->getRepository(Invoice::class)->find(8),
            ]
        );
    }
}
