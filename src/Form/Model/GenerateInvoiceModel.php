<?php

namespace App\Form\Model;

use Doctrine\Common\Collections\ArrayCollection;

class GenerateInvoiceModel extends AbstractGenerateReceiptInvoiceModel
{
    private ArrayCollection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getItems(): ArrayCollection
    {
        return $this->items;
    }

    public function setItems(ArrayCollection $items): self
    {
        $this->items = $items;

        return $this;
    }

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

    public function getTotalAmount(): float
    {
        $result = 0.0;
        /** @var GenerateInvoiceItemModel $item */
        foreach ($this->getItems() as $item) {
            $result += $item->getTotal();
        }

        return $result;
    }
}
