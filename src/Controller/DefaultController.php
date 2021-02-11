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
use App\Manager\MailchimpManager;
use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController.
 */
class DefaultController extends AbstractController
{
    const ENV_DEV = 'dev';

    /**
     * @Route("/", name="app_homepage")
     *
     * @return Response
     */
    public function indexAction(Request $request, MailchimpManager $mailchimpManager, NotificationService $messenger)
    {
        $teachers = $this->getDoctrine()->getRepository(Teacher::class)->findAllEnabledSortedByPosition();

        $contact = new NewsletterContact();
        $newsletterForm = $this->createForm(ContactHomepageType::class, $contact);
        $newsletterForm->handleRequest($request);

        if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
            // Persist new contact message into DB
            $em = $this->getDoctrine()->getManager();
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

        return $this->render('Front/homepage.html.twig',
            [
                'teachers' => $teachers,
                'newsletterForm' => $newsletterForm->createView(),
            ]
        );
    }

    private function setFlashMessageAndEmailNotifications(NotificationService $messenger, NewsletterContact $newsletterContact)
    {
        // Send email notifications
        if (0 != $messenger->sendCommonNewsletterUserNotification($newsletterContact)) {
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
     *
     * @return Response
     */
    public function servicesAction()
    {
        $services = $this->getDoctrine()->getRepository(Service::class)->findAllEnabledSortedByPosition();

        return $this->render(
            'Front/services.html.twig',
            ['services' => $services]
        );
    }

    /**
     * @Route("/academia", name="app_academy")
     *
     * @return Response
     */
    public function academyAction()
    {
        return $this->render('Front/academy.html.twig');
    }

    /**
     * @Route("/contacte", name="app_contact")
     *
     * @return Response
     */
    public function contactAction(Request $request, NotificationService $messenger)
    {
        $contactMessage = new ContactMessage();
        $contactMessageForm = $this->createForm(ContactMessageType::class, $contactMessage);
        $contactMessageForm->handleRequest($request);

        if ($contactMessageForm->isSubmitted() && $contactMessageForm->isValid()) {
            // Persist new contact message into DB
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactMessage);
            $em->flush();
            // Send email notifications
            if (0 != $messenger->sendCommonUserNotification($contactMessage)) {
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
            ['contactMessageForm' => $contactMessageForm->createView()]
        );
    }

    /**
     * @Route("/preinscripcions", name="app_pre_register")
     *
     * @return Response
     */
    public function preRegistersAction(Request $request, NotificationService $messenger)
    {
        $preRegister = new PreRegister();
        $preRegisterForm = $this->createForm(PreRegisterType::class, $preRegister);
        $preRegisterForm->handleRequest($request);

        if ($preRegisterForm->isSubmitted() && $preRegisterForm->isValid()) {
            // Persist new pre-register record into DB
            $em = $this->getDoctrine()->getManager();
            $preRegister->setEnabled(false);
            $em->persist($preRegister);
            $em->flush();
            if (0 != $messenger->sendPreRegisterAdminNotification($preRegister)) {
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
            ['preRegisterForm' => $preRegisterForm->createView()]
        );
    }

    /**
     * @Route("/politica-de-privacitat", name="app_privacy_policy")
     *
     * @return Response
     */
    public function privacyPolicyAction()
    {
        return $this->render('Front/privacy_policy.html.twig', []);
    }

    /**
     * @Route("/credits", name="app_credits")
     *
     * @return Response
     */
    public function creditsAction()
    {
        return $this->render('Front/credits.html.twig');
    }

    /**
     * @Route("/test-email", name="app_test_email")
     *
     * @return Response
     */
    public function testEmailAction(KernelInterface $kernel)
    {
        if ('prod' === $kernel->getEnvironment()) {
            throw new AccessDeniedHttpException();
        }

        $invoice = $this->getDoctrine()->getRepository(Invoice::class)->find(8);

        return $this->render('Mails/invoice_pdf_notification.html.twig', [
            'invoice' => $invoice,
        ]);
    }
}
