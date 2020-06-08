<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use TCPDF;

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
     * @param TCPDF  $pdf
     *
     * @return void
     *
     * @throws TransportExceptionInterface
     */
    public function sendEmailWithPdfAttached($from, $toEmail, $toName, $subject, $body, $pdfFilename, TCPDF $pdf)
    {
        $message = $this->buildEmail($from, $toEmail, $subject, $body, null, $toName);
        $pathToTemporaryStoredPdfFile = DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$pdfFilename;
        $pdf->Output($pathToTemporaryStoredPdfFile, 'F');
        $message->attachFromPath($pathToTemporaryStoredPdfFile, $pdfFilename, 'application/pdf');

        $this->mailer->send($message);
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
            ->from(new Address($from))
            ->to(new Address($toEmail, is_null($toName) ? '' : $toName))
            ->html($body);
        if (!is_null($replyAddress)) {
            $message->replyTo(new Address($replyAddress));
        }

        return $message;
    }
}
