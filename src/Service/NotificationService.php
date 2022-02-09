<?php

namespace App\Service;

use App\Entity\ContactMessage;
use App\Entity\Invoice;
use App\Entity\NewsletterContact;
use App\Entity\PreRegister;
use App\Entity\Receipt;
use App\Entity\StudentAbsence;
use App\Entity\User;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use TCPDF;
use Twig\Environment;

class NotificationService
{
    private CourierService $messenger;
    private Environment $twig;
    private string $amd; // system's App Mail Destionation
    private string $pub; // project URL base

    public function __construct(CourierService $messenger, Environment $twig, ParameterBagInterface $pb)
    {
        $this->messenger = $messenger;
        $this->twig = $twig;
        $this->amd = $pb->get('mailer_destination');
        $this->pub = $pb->get('project_url_base');
    }

    /**
     * Send a common notification mail to frontend user.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendCommonUserNotification(ContactMessage $contactMessage): int
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
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a contact form notification to administrator.
     */
    public function sendAdminNotification(ContactMessage $contactMessage): int
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
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a contact form notification to admin user.
     */
    public function sendContactAdminNotification(ContactMessage $contactMessage): int
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
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send backend answer notification to web user.
     */
    public function sendUserBackendNotification(ContactMessage $contactMessage): int
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
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a newsletter subscription form notification to admin user.
     */
    public function sendNewsletterSubscriptionAdminNotification(NewsletterContact $newsletterContact): int
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
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a newsletter subscription form notification to admin user on Mailchimp failure.
     */
    public function sendFailureNewsletterSubscriptionAdminNotification(NewsletterContact $newsletterContact): int
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
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a common notification mail to frontend user.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendCommonNewsletterUserNotification(NewsletterContact $newsletterContact): int
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
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send attached remainder receipt PDF to customer.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendReceiptReminderPdfNotification(Receipt $receipt, TCPDF $pdf): int
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
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send attached receipt PDF to customer.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendReceiptPdfNotification(Receipt $receipt, TCPDF $pdf): int
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
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send attached invoice PDF to customer.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendInvoicePdfNotification(Invoice $invoice, TCPDF $pdf): int
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
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a student absence notification mail to student or parent email.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendStudentAbsenceNotification(StudentAbsence $studentAbsence): int
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
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send a preregister form notification to admin user.
     */
    public function sendPreRegisterAdminNotification(PreRegister $preRegister): int
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
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }

    public function sendUserPasswordRequestNotification(User $user, ResetPasswordToken $resetToken): int
    {
        $result = 1;
        try {
            $this->messenger->sendEmail(
                $this->amd,
                $user->getEmail(),
                'Enllaç recuperació contrasenya aplicació gestió web '.$this->pub,
                $this->twig->render('Mails/reset_password.html.twig', [
                    'resetToken' => $resetToken,
                ]),
                $user->getFullName()
            );
        } catch (TransportExceptionInterface | Exception $exception) {
            $result = 0;
        }

        return $result;
    }
}
