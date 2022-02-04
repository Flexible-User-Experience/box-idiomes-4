<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReceiptLineRepository")
 * @ORM\Table(name="receipt_line")
 */
class ReceiptLine extends AbstractReceiptInvoiceLine
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Receipt", inversedBy="lines")
     * @ORM\JoinColumn(name="receipt_id", referencedColumnName="id")
     */
    private Receipt $receipt;

    public function getReceipt(): Receipt
    {
        return $this->receipt;
    }

    public function setReceipt(Receipt $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }
}
