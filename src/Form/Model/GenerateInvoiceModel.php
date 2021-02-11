<?php

namespace App\Form\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class GenerateInvoiceModel.
 *
 * @category FormModel
 */
class GenerateInvoiceModel extends AbstractGenerateReceiptInvoiceModel
{
    /**
     * @var GenerateInvoiceItemModel[]
     */
    private $items;

    /**
     * Methods.
     */

    /**
     * GenerateInvoiceModel constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return GenerateInvoiceItemModel[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param GenerateInvoiceItemModel[] $items
     *
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return $this
     */
    public function addItem(GenerateInvoiceItemModel $item)
    {
        $this->items->add($item);

        return $this;
    }

    /**
     * @return $this
     */
    public function removeItem(GenerateInvoiceItemModel $item)
    {
        $this->items->removeElement($item);

        return $this;
    }

    /**
     * @return float
     */
    public function getTotalAmount()
    {
        $result = 0.0;
        /** @var GenerateInvoiceItemModel $item */
        foreach ($this->getItems() as $item) {
            $result = $result + $item->getTotal();
        }

        return $result;
    }
}
