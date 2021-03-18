<?php

namespace App\Manager;

use App\Repository\EventRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractGenerateReceiptInvoiceFormManager
{
    protected LoggerInterface $logger;
    protected KernelInterface $kernel;
    protected EntityManagerInterface $em;
    protected TranslatorInterface $ts;
    protected StudentRepository $sr;
    protected EventRepository $er;

    public function __construct(LoggerInterface $logger, KernelInterface $kernel, EntityManagerInterface $em, TranslatorInterface $ts, StudentRepository $sr, EventRepository $er)
    {
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->em = $em;
        $this->ts = $ts;
        $this->sr = $sr;
        $this->er = $er;
    }

    protected function parseStringToFloat($value): float
    {
        $stringParsedValue = str_replace(['.', ','], ['', '.'], $value);

        return (float) $stringParsedValue;
    }
}
