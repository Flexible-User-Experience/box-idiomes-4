<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ReceiptLine.
 *
 * @category Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\ReceiptLineRepository")
 * @ORM\Table(name="receipt_line")
 */
class ReceiptLine extends AbstractReceiptInvoiceLine
{
    /**
     * @var Receipt
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Receipt", inversedBy="lines")
     * @ORM\JoinColumn(name="receipt_id", referencedColumnName="id")
     */
    private $receipt;

    /**
     * Methods.
     */

    /**
     * @return Receipt
     */
    public function getReceipt()
    {
        return $this->receipt;
    }

    /**
     * @return $this
     */
    public function setReceipt(Receipt $receipt)
    {
        $this->receipt = $receipt;

        return $this;
    }
}
