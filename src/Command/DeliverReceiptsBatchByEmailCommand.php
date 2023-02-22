<?php

namespace App\Command;

use App\Entity\Receipt;
use App\Enum\StudentPaymentEnum;
use App\Pdf\ReceiptBuilderPdf;
use App\Pdf\ReceiptReminderBuilderPdf;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

// TODO use an async Message queue instead
class DeliverReceiptsBatchByEmailCommand extends Command
{
    private EntityManagerInterface $em;
    private LoggerInterface $logger;
    private ReceiptReminderBuilderPdf $rrbp;
    private ReceiptBuilderPdf $rbp;
    private NotificationService $messenger;
    protected static $defaultDescription = 'Deliver a receipts batch by email';

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, ReceiptReminderBuilderPdf $rrbp, ReceiptBuilderPdf $rbp, NotificationService $messenger)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->rrbp = $rrbp;
        $this->rbp = $rbp;
        $this->messenger = $messenger;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:deliver:receipts:batch')
            ->addArgument(
                'receipts',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'The receipts ID list stored in database to deliver'
            )
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will deliver the email'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Welcome to '.$this->getName().' command</info>');
        /** @var Receipt[]|array $receipts */
        $receipts = $this->em->getRepository(Receipt::class)->findByIdsArray($input->getArgument('receipts'));
        if (count($receipts) > 0) {
            $output->writeln('building PDF receipts IDs# number '.implode(' ', $input->getArgument('receipts')));
            $output->writeln('searched receipts found '.count($receipts));
            /** @var Receipt $receipt */
            foreach ($receipts as $receipt) {
                $output->write('building PDF receipt number '.$receipt->getReceiptNumber().'... ');
                if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER === $receipt->getMainSubject()->getPayment()) {
                    // build receipt PDF
                    $pdf = $this->rbp->build($receipt);
                } else {
                    // build receipt reminder PDF
                    $pdf = $this->rrbp->build($receipt);
                }
                $output->writeln('<info>OK</info>');
                $this->logger->info('[DRBBEC] PDF receipt #'.$receipt->getId().' number '.$receipt->getReceiptNumber().' succesfully build.');
                if ($input->getOption('force')) {
                    $output->write('delivering PDF receipt number '.$receipt->getReceiptNumber().'... ');
                    if ($receipt->getMainEmail()) {
                        if (StudentPaymentEnum::BANK_ACCOUNT_NUMBER === $receipt->getMainSubject()->getPayment()) {
                            // send receipt PDF
                            $result = $this->messenger->sendReceiptPdfNotification($receipt, $pdf);
                        } else {
                            // send receipt reminder PDF
                            $result = $this->messenger->sendReceiptReminderPdfNotification($receipt, $pdf);
                        }
                        if (0 === $result) {
                            $output->writeln('<error>KO</error>');
                            $this->logger->error('[DRBBEC] delivering PDF receipt #'.$receipt->getId().' number '.$receipt->getReceiptNumber().' failed.');
                        } else {
                            $output->writeln('<info>OK</info>');
                            $this->logger->info('[DRBBEC] PDF receipt #'.$receipt->getId().' number '.$receipt->getReceiptNumber().' succesfully delivered.');
                        }
                    } else {
                        $output->writeln('<comment>KO</comment>');
                        $this->logger->error('[DRBBEC] PDF receipt #'.$receipt->getId().' number '.$receipt->getReceiptNumber().' not delivered. Missing email in '.$receipt->getMainSubject()->getFullCanonicalName().'.');
                    }
                }
            }
        } else {
            $output->writeln('<error>No receipts with IDs# '.implode(', ', $input->getArgument('receipt')).' found. Nothing send.</error>');
        }
        $output->writeln('<info>EOF.</info>');

        return Command::SUCCESS;
    }
}
