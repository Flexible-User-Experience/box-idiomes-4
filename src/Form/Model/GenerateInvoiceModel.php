<?php

namespace App\Form\Model;

class GenerateInvoiceModel extends AbstractGenerateReceiptInvoiceModel
{
    public function addItem(GenerateInvoiceItemModel $item): self
    {
        $this->items->add($item);

        return $this;
    }

    public function removeItem(GenerateInvoiceItemModel $item): self
    {
        $this->items->removeElement($item);

        return $this;
    }
}
