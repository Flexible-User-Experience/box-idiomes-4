<?php

namespace App\Form\Model;

class GenerateReceiptModel extends AbstractGenerateReceiptInvoiceModel
{
    public function addItem(GenerateReceiptItemModel $item): self
    {
        $this->items->add($item);

        return $this;
    }

    public function removeItem(GenerateReceiptItemModel $item): self
    {
        $this->items->removeElement($item);

        return $this;
    }
}
