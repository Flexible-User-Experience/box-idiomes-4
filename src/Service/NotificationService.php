<?php

namespace App\Service;

use App\Entity\ContactMessage;
use App\Entity\Invoice;
use App\Entity\MailingStudentsNotificationMessage;
use App\Entity\NewsletterContact;
use App\Entity\PreRegister;
use App\Entity\Receipt;
use App\Entity\Student;
use App\Entity\StudentAbsence;
use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use Twig\Environment;

class NotificationService
{
    private SmartAssetsHelperService $sahs;
    private CourierService $messenger;
    private Environment $twig;
    private string $amd; // system's App Mail Destionation
    private string $pwt; // project web title
    private string $pub; // project URL base

    public function __construct(SmartAssetsHelperService $sahs, CourierService $messenger, Environment $twig, ParameterBagInterface $pb)
    {
        $this->sahs = $sahs;
        $this->messenger = $messenger;
        $this->twig = $twig;
        $this->amd = $pb->get('mailer_destination');
        $this->pwt = $pb->get('project_web_title');
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
                $this->pub.' · Missatge de contacte pàgina web enviat',
                $this->twig->render('Mails/common_user_notification.html.twig', [
                    'contact' => $contactMessage,
                ])
            );
        } catch (TransportExceptionInterface|\Exception) {
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
        } catch (TransportExceptionInterface|\Exception) {
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
            if ($contactMessage->getDocument()) {
                $this->messenger->sendEmailWithAttachedFile(
                    $this->amd,
                    $contactMessage->getEmail(),
                    $contactMessage->getName(),
                    $this->pub.' · Resposta missatge de contacte pàgina web',
                    $this->twig->render('Mails/contact_form_user_backend_notification.html.twig', [
                        'contact' => $contactMessage,
                    ]),
                    $this->sahs->getAbsoluteAssetFilePath($this->sahs->getContactMessageAttatchmentPath($contactMessage)),
                    $contactMessage->getDocument()
                );
            } else {
                $this->messenger->sendEmail(
                    $this->amd,
                    $contactMessage->getEmail(),
                    $this->pub.' · Resposta missatge de contacte pàgina web',
                    $this->twig->render('Mails/contact_form_user_backend_notification.html.twig', [
                        'contact' => $contactMessage,
                    ])
                );
            }
        } catch (TransportExceptionInterface|\Exception) {
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
        } catch (TransportExceptionInterface|\Exception) {
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
        } catch (TransportExceptionInterface|\Exception) {
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
        } catch (TransportExceptionInterface|\Exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send attached remainder receipt PDF to customer.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendReceiptReminderPdfNotification(Receipt $receipt, \TCPDF $pdf): int
    {
        $result = 1;
        try {
            $this->messenger->sendEmailWithPdfAttached(
                $this->amd,
                $receipt->getMainEmail(),
                $receipt->getMainEmailName(),
                'Recordatori de pagament rebut '.$this->pwt.' núm. '.$receipt->getReceiptNumber(),
                $this->twig->render('Mails/receipt_reminder_pdf_notification.html.twig', [
                    'receipt' => $receipt,
                ]),
                'receipt_'.$receipt->getSluggedReceiptNumber().'.pdf',
                $pdf
            );
        } catch (TransportExceptionInterface|\Exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send attached receipt PDF to customer.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendReceiptPdfNotification(Receipt $receipt, \TCPDF $pdf): int
    {
        $result = 1;
        try {
            $this->messenger->sendEmailWithPdfAttached(
                $this->amd,
                $receipt->getMainEmail(),
                $receipt->getMainEmailName(),
                'Rebut '.$this->pwt.' núm. '.$receipt->getReceiptNumber(),
                $this->twig->render('Mails/receipt_pdf_notification.html.twig', [
                    'receipt' => $receipt,
                ]),
                'receipt_'.$receipt->getSluggedReceiptNumber().'.pdf',
                $pdf
            );
        } catch (TransportExceptionInterface|\Exception) {
            $result = 0;
        }

        return $result;
    }

    /**
     * Send attached invoice PDF to customer.
     *
     * @return int If is 0 failure otherwise amount of recipients
     */
    public function sendInvoicePdfNotification(Invoice $invoice, \TCPDF $pdf): int
    {
        $result = 1;
        try {
            $this->messenger->sendEmailWithPdfAttached(
                $this->amd,
                $invoice->getMainEmail(),
                $invoice->getMainEmailName(),
                'Factura '.$this->pwt.' núm. '.$invoice->getInvoiceNumber(),
                $this->twig->render('Mails/invoice_pdf_notification.html.twig', [
                    'invoice' => $invoice,
                ]),
                'invoice_'.$invoice->getSluggedInvoiceNumber().'.pdf',
                $pdf
            );
        } catch (TransportExceptionInterface|\Exception) {
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
        } catch (TransportExceptionInterface|\Exception) {
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
        } catch (TransportExceptionInterface|\Exception) {
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
                null,
                $user->getFullName()
            );
        } catch (TransportExceptionInterface|\Exception) {
            $result = 0;
        }

        return $result;
    }

    public function sendMailingStudentsNotification(Student $student, MailingStudentsNotificationMessage $notidication): bool
    {
        // TODO
        return false;
    }
}
