<?php

namespace App\Message;

class NewReceiptGroupCreatedMessage
{
    private int $receiptGroupId;

    public function __construct(int $receiptGroupId)
    {
        $this->receiptGroupId = $receiptGroupId;
    }

    public function getReceiptGroupId(): int
    {
        return $this->receiptGroupId;
    }
}
