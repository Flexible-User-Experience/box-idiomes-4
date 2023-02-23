<?php

namespace App\Message\Handler;

use App\Message\NewReceiptCreatedEmailMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewReceiptCreatedEmailMessageHandler implements MessageHandlerInterface
{
    public function __invoke(NewReceiptCreatedEmailMessage $message)
    {
    }
}
