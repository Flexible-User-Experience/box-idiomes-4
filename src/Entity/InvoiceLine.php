<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class InvoiceLine.
 *
 * @category Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceLineRepository")
 * @ORM\Table(name="invoice_line")
 */
class InvoiceLine extends AbstractReceiptInvoiceLine
{
    /**
     * @var Invoice
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Invoice", inversedBy="lines")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     */
    private $invoice;

    /**
     * Methods.
     */

    /**
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @return $this
     */
    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }
}
