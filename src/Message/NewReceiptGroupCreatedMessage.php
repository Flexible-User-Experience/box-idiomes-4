<?php

namespace App\Message;

class NewReceiptGroupCreatedMessage
{
    private array $selectedModelIds;

    public function __construct(array $selectedModelIds)
    {
        $this->selectedModelIds = $selectedModelIds;
    }

    public function getSelectedModelIdsArray(): array
    {
        return $this->selectedModelIds;
    }
}
