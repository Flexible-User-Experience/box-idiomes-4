<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * Class CourierService.
 *
 * @category Service
 */
class CourierService
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * Methods.
     */

    /**
     * CourierService constructor.
     *
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Build an email.
     *
     * @param string      $from
     * @param string      $toEmail
     * @param string      $subject
     * @param string      $body
     * @param string|null $replyAddress
     * @param string|null $toName
     *
     * @return Email
     */
    private function buildEmail($from, $toEmail, $subject, $body, $replyAddress = null, $toName = null)
    {
        $message = new Email();
        $message
            ->subject($subject)
            ->from($from)
            ->to($toEmail, $toName)
            ->text($body);
        if (!is_null($replyAddress)) {
            $message->replyTo($replyAddress);
        }

        return $message;
    }

    /**
     * Send an email.
     *
     * @param string $from
     * @param string $toEmail
     * @param string $subject
     * @param string $body
     * @param string|null $replyAddress
     * @param string|null $toName
     *
     * @return void
     *
     * @throws TransportExceptionInterface
     */
    public function sendEmail($from, $toEmail, $subject, $body, $replyAddress = null, $toName = null)
    {
        $message = $this->buildEmail($from, $toEmail, $subject, $body, $replyAddress, $toName);

        $this->mailer->send($message);
    }

    /**
     * Send an email with an attatchment PDF.
     *
     * @param string $from
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $body
     * @param string $pdfFilename
     * @param \TCPDF $pdf
     *
     * @return void
     *
     * @throws TransportExceptionInterface
     */
    public function sendEmailWithPdfAttached($from, $toEmail, $toName, $subject, $body, $pdfFilename, \TCPDF $pdf)
    {
        $swiftAttatchment = new \Swift_Attachment($pdf->Output($pdfFilename, 'S'), $pdfFilename, 'application/pdf');
        $message = $this->buildSwiftMesage($from, $toEmail, $subject, $body, null, $toName);
        $message->attach($swiftAttatchment);

        $this->mailer->send($message);
    }
}
