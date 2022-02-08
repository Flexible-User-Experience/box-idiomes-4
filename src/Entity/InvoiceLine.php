<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceLineRepository")
 * @ORM\Table(name="invoice_line")
 */
class InvoiceLine extends AbstractReceiptInvoiceLine
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Invoice", inversedBy="lines")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     */
    private Invoice $invoice;

    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(Invoice $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }
}
