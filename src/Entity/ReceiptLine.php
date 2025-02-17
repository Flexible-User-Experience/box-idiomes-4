<?php

namespace App\Entity;

use App\Repository\ReceiptLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReceiptLineRepository::class)]
#[ORM\Table(name: 'receipt_line')]
class ReceiptLine extends AbstractReceiptInvoiceLine
{
    #[ORM\ManyToOne(targetEntity: Receipt::class, inversedBy: 'lines')]
    #[ORM\JoinColumn(name: 'receipt_id', referencedColumnName: 'id')]
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
