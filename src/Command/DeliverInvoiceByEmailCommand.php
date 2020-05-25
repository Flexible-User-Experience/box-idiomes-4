<?php

namespace App\Command;

use App\Entity\Invoice;
use App\Service\NotificationService;
use App\Pdf\InvoiceBuilderPdf;
use Exception;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DeliverInvoiceByEmailCommand.
 *
 * @category Command
 */
class DeliverInvoiceByEmailCommand extends Command
{
    /**
     * Configure command.
     */
    protected function configure()
    {
        $this
            ->setName('app:deliver:invoice')
            ->setDescription('Deliver an invoice previously generated by email')
            ->addArgument(
                'invoice',
                InputArgument::REQUIRED,
                'The invoice ID stored in database'
            )
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will deliver the email'
            )
        ;
    }

    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Welcome to '.$this->getName().' command</info>');
        /** @var Invoice|null $invoice */
        $invoice = $this->getContainer()->get('doctrine')->getRepository(Invoice::class)->find(intval($input->getArgument('invoice')));
        if ($invoice) {
            $output->write('building PDF invoice number '.$invoice->getInvoiceNumber().'... ');
            /** @var InvoiceBuilderPdf $ibp */
            $ibp = $this->getContainer()->get('app.invoice_pdf_builder');
            $pdf = $ibp->build($invoice);
            $output->writeln('<info>OK</info>');
            if ($input->getOption('force')) {
                /** @var Logger $logger */
                $logger = $this->getContainer()->get('monolog.logger.email');
                /** @var NotificationService $messenger */
                $messenger = $this->getContainer()->get('app.notification');
                $result = $messenger->sendInvoicePdfNotification($invoice, $pdf);
                $output->write('delivering PDF invoice number '.$invoice->getInvoiceNumber().'... ');
                if ($invoice->getMainEmail()) {
                    if (0 === $result) {
                        $output->writeln('<error>KO</error>');
                        $logger->error('[DIBEC] delivering PDF invoice number '.$invoice->getInvoiceNumber().' failed.');
                    } else {
                        $output->writeln('<info>OK</info>');
                        $logger->info('[DIBEC] PDF invoice number '.$invoice->getInvoiceNumber().' succesfully delivered.');
                    }
                } else {
                    $output->writeln('<comment>KO</comment>');
                    $logger->error('[DIBEC] PDF receipt #'.$invoice->getId().' number '.$invoice->getInvoiceNumber().' not delivered. Missing email in '.$invoice->getMainSubject()->getFullCanonicalName().'.');
                }
            }
        } else {
            $output->writeln('<error>No invoice with ID#'.intval($input->getArgument('invoice')).' found. Nothing send.</error>');
        }

        $output->writeln('<info>EOF.</info>');
    }
}
