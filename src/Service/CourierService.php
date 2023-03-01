<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class CourierService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(string $from, string $toEmail, string $subject, string $body, ?string $replyAddress = null, ?string $toName = null): void
    {
        $message = $this->buildEmail($from, $toEmail, $subject, $body, $replyAddress, $toName);
        $this->mailer->send($message);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailWithPdfAttached(string $from, string $toEmail, string $toName, string $subject, string $body, string $pdfFilename, \TCPDF $pdf): void
    {
        $message = $this->buildEmail($from, $toEmail, $subject, $body, null, $toName);
        $pathToTemporaryStoredPdfFile = DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$pdfFilename;
        $pdf->Output($pathToTemporaryStoredPdfFile, 'F');
        $message->attachFromPath($pathToTemporaryStoredPdfFile, $pdfFilename, 'application/pdf');

        $this->mailer->send($message);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailWithAttachedFile(string $from, string $toEmail, string $toName, string $subject, string $body, string $filepath, string $filename): void
    {
        $message = $this->buildEmail($from, $toEmail, $subject, $body, null, $toName);
        $message->attachFromPath($filepath, $filename);

        $this->mailer->send($message);
    }

    private function buildEmail(string $from, string $toEmail, string $subject, string $body, ?string $replyAddress = null, ?string $toName = null): Email
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
