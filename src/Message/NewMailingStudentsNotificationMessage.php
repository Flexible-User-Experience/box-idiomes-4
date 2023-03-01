<?php

namespace App\Message;

class NewMailingStudentsNotificationMessage
{
    private int $studentId;
    private int $mailingStudentsNotificationMessageId;

    public function __construct(int $studentId, int $mailingStudentsNotificationMessageId)
    {
        $this->studentId = $studentId;
        $this->mailingStudentsNotificationMessageId = $mailingStudentsNotificationMessageId;
    }

    public function getStudentId(): int
    {
        return $this->studentId;
    }

    public function getMailingStudentsNotificationMessageId(): int
    {
        return $this->mailingStudentsNotificationMessageId;
    }
}
