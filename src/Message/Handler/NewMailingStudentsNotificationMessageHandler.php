<?php

namespace App\Message\Handler;

use App\Message\NewMailingStudentsNotificationMessage;
use App\Repository\MailingStudentsNotificationMessageRepository;
use App\Repository\StudentRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewMailingStudentsNotificationMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private EntityManagerInterface $em;
    private StudentRepository $sr;
    private MailingStudentsNotificationMessageRepository $msnmr;
    private NotificationService $ns;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, StudentRepository $sr, MailingStudentsNotificationMessageRepository $msnmr, NotificationService $ns)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->sr = $sr;
        $this->msnmr = $msnmr;
        $this->ns = $ns;
    }

    public function __invoke(NewMailingStudentsNotificationMessage $message)
    {
        $student = $this->sr->find($message->getStudentId());
        if ($student) {
            $notification = $this->msnmr->find($message->getMailingStudentsNotificationMessageId());
            if ($notification) {
                $hasBeenSuccessfulySend = $this->ns->sendMailingStudentsNotification($student, $notification);
                if (!$hasBeenSuccessfulySend) {
                    $notification->setTotalDeliveredErrors($notification->getTotalDeliveredErrors() + 1);
                    $this->em->flush();
                    $this->logger->error('[NMSNM] Mailing delivery error found in Student ID#'.$message->getStudentId().' and MailingStudentsNotificationMessage ID#'.$message->getMailingStudentsNotificationMessageId());
                }
            } else {
                $this->logger->error('[NMSNM] MailingStudentsNotificationMessage ID#'.$message->getMailingStudentsNotificationMessageId().' NOT found');
            }
        } else {
            $this->logger->error('[NMSNM] Student ID#'.$message->getStudentId().' NOT found');
        }
    }
}
