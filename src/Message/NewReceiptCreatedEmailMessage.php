<?php

namespace App\Message;

class NewReceiptCreatedEmailMessage
{
    private int $receiptId;

    public function __construct(int $receiptId)
    {
        $this->receiptId = $receiptId;
    }

    public function getReceiptId(): int
    {
        return $this->receiptId;
    }
}
