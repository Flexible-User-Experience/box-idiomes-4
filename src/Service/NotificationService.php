<?php

namespace App\Service;

use App\Entity\ContactMessage;
use App\Entity\Invoice;
use App\Entity\NewsletterContact;
use App\Entity\PreRegister;
use App\Entity\Receipt;
use App\Entity\StudentAbsence;
use Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use TCPDF;
use Twig\Environment;

/**
 * Class NotificationService.
 *
 * @category Service
 */
class NotificationService
{
    /**
     * @var CourierService
     */
    private $messenger;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var string system's App Mail Destionation
     */
    private $amd;

    /**
     * @var string Project URL Base
     */
    private $pub;

    /**
     * Methods.
     */

    /**
     * NotificationService constructor.
     *
     * @param string $amd
     * @param string $pub
     */
    public function __construct(CourierService $messenger, Environment $twig, $amd, $pub)
    {
        $this->messenger = $messenger;
        $this->twig = $twig;
        $this->amd = $amd;
        $this->pub = $pub;
    }

    /**
     * Send a common notification mail to frontend user.
     *
     * @return int If is 0 failure otherwise amount of recipients
     * @return int
     */
    public function sendCommonUserNotification(ContactMessage $contactMessage)
    {
        $result = 1;
        try {
            $this->messenger->sendEmail(
                $this->amd,
                $contactMessage->getEmail(),
                'Notificació pàgina web '.$this->pub,
                $this->twig->render('Mails/common_user_notification.html.twig', [
                    'contact' => $contactMessage,
                ])
            );
        } catch (TransportExceptionInterface $exception) {
            $result = 0;
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a contact form notification to administrator.
     *
     * @return int
     */
    public function sendAdminNotification(ContactMessage $contactMessage)
    {
        $result = 1;
        try {
            $this->messenger->sendEmail(
                $contactMessage->getEmail(),
                $this->amd,
                $this->pub.' contact form received',
                $this->twig->render('Mails/contact_form_admin_notification.html.twig', [
                    'contact' => $contactMessage,
                ])
            );
        } catch (TransportExceptionInterface $exception) {
            $result = 0;
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a contact form notification to admin user.
     *
     * @return int
     */
    public function sendContactAdminNotification(ContactMessage $contactMessage)
    {
        $result = 1;
        try {
            $this->messenger->sendEmail(
                $this->amd,
                $this->amd,
                'Missatge de contacte pàgina web '.$this->pub,
                $this->twig->render('Mails/contact_form_admin_notification.html.twig', [
                    'contact' => $contactMessage,
                ])
            );
        } catch (TransportExceptionInterface $exception) {
            $result = 0;
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send backend answer notification to web user.
     *
     * @return int
     */
    public function sendUserBackendNotification(ContactMessage $contactMessage)
    {
        $result = 1;
        try {
            $this->messenger->sendEmail(
                $this->amd,
                $contactMessage->getEmail(),
                $this->pub.' contact form answer',
                $this->twig->render('Mails/contact_form_user_backend_notification.html.twig', [
                    'contact' => $contactMessage,
                ])
            );
        } catch (TransportExceptionInterface $exception) {
            $result = 0;
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a newsletter subscription form notification to admin user.
     *
     * @return int
     */
    public function sendNewsletterSubscriptionAdminNotification(NewsletterContact $newsletterContact)
    {
        $result = 1;
        try {
            $this->messenger->sendEmail(
                $this->amd,
                $this->amd,
                'Missatge de newsletter pàgina web '.$this->pub,
                $this->twig->render('Mails/newsletter_form_admin_notification.html.twig', [
                    'contact' => $newsletterContact,
                ]),
                $newsletterContact->getEmail()
            );
        } catch (TransportExceptionInterface $exception) {
            $result = 0;
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a newsletter subscription form notification to admin user on Mailchimp failure.
     *
     * @return int
     */
    public function sendFailureNewsletterSubscriptionAdminNotification(NewsletterContact $newsletterContact)
    {
        $result = 1;
        try {
            $this->messenger->sendEmail(
                $this->amd,
                $this->amd,
                'Missatge de newsletter pàgina web '.$this->pub,
                $this->twig->render('Mails/newsletter_failure_admin_notification.html.twig', [
                    'contact' => $newsletterContact,
                ]),
                $newsletterContact->getEmail()
            );
        } catch (TransportExceptionInterface $exception) {
            $result = 0;
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a common notification mail to frontend user.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendCommonNewsletterUserNotification(NewsletterContact $newsletterContact)
    {
        $result = 1;
        try {
            $this->messenger->sendEmail(
                $this->amd,
                $newsletterContact->getEmail(),
                'Notificació newsletter pàgina web '.$this->pub,
                $this->twig->render('Mails/common_newsletter_user_notification.html.twig', [
                    'contact' => $newsletterContact,
                ])
            );
        } catch (TransportExceptionInterface $exception) {
            $result = 0;
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send attached remainder receipt PDF to customer.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendReceiptReminderPdfNotification(Receipt $receipt, TCPDF $pdf)
    {
        $result = 1;
        try {
            $this->messenger->sendEmailWithPdfAttached(
                $this->amd,
                $receipt->getMainEmail(),
                $receipt->getMainEmailName(),
                'Recordatori de pagament rebut Box Idiomes núm. '.$receipt->getReceiptNumber(),
                $this->twig->render('Mails/receipt_reminder_pdf_notification.html.twig', [
                    'receipt' => $receipt,
                ]),
                'receipt_'.$receipt->getSluggedReceiptNumber().'.pdf',
                $pdf
            );
        } catch (TransportExceptionInterface $exception) {
            $result = 0;
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send attached receipt PDF to customer.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendReceiptPdfNotification(Receipt $receipt, TCPDF $pdf)
    {
        $result = 1;
        try {
            $this->messenger->sendEmailWithPdfAttached(
                $this->amd,
                $receipt->getMainEmail(),
                $receipt->getMainEmailName(),
                'Rebut Box Idiomes núm. '.$receipt->getReceiptNumber(),
                $this->twig->render('Mails/receipt_pdf_notification.html.twig', [
                    'receipt' => $receipt,
                ]),
                'receipt_'.$receipt->getSluggedReceiptNumber().'.pdf',
                $pdf
            );
        } catch (TransportExceptionInterface $exception) {
            $result = 0;
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send attached invoice PDF to customer.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendInvoicePdfNotification(Invoice $invoice, TCPDF $pdf)
    {
        $result = 1;
        try {
            $this->messenger->sendEmailWithPdfAttached(
                $this->amd,
                $invoice->getMainEmail(),
                $invoice->getMainEmailName(),
                'Factura Box Idiomes núm. '.$invoice->getInvoiceNumber(),
                $this->twig->render('Mails/invoice_pdf_notification.html.twig', [
                    'invoice' => $invoice,
                ]),
                'invoice_'.$invoice->getSluggedInvoiceNumber().'.pdf',
                $pdf
            );
        } catch (TransportExceptionInterface $exception) {
            $result = 0;
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a student absence notification mail to student or parent email.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendStudentAbsenceNotification(StudentAbsence $studentAbsence)
    {
        $result = 1;
        try {
            $this->messenger->sendEmail(
                $this->amd,
                $studentAbsence->getStudent()->getMainEmailSubject(),
                'Falta a classe el dia '.$studentAbsence->getDayString(),
                $this->twig->render('Mails/student_absence_notification.html.twig', [
                    'studentAbsence' => $studentAbsence,
                ])
            );
        } catch (TransportExceptionInterface $exception) {
            $result = 0;
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a preregister form notification to admin user.
     *
     * @return int
     */
    public function sendPreRegisterAdminNotification(PreRegister $preRegister)
    {
        $result = 1;
        try {
            $this->messenger->sendEmail(
                $this->amd,
                $this->amd,
                'Registre formulari preinscripció pàgina web '.$this->pub,
                $this->twig->render('Mails/pre_register_form_admin_notification.html.twig', [
                    'preRegister' => $preRegister,
                ]),
                $preRegister->getEmail()
            );
        } catch (TransportExceptionInterface $exception) {
            $result = 0;
        } catch (Exception $e) {
            $result = 0;
        }

        return $result;
    }
}
