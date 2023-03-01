<?php

namespace App\Command;

use App\Entity\ClassGroup;
use App\Entity\Invoice;
use App\Entity\Receipt;
use App\Entity\Student;
use App\Entity\TrainingCenter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitializeDefaultTrainingCenterRelationshipsCommand extends Command
{
    private EntityManagerInterface $em;
    protected static $defaultDescription = 'Initialize default training center relationships';

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:initialize:training:center')
            ->addArgument(
                'tcid',
                InputArgument::REQUIRED,
                'TrainingCenter ID to set into Student, ClassGroup, Receipt and Invoice entities relationship'
            )
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will persist applied changes'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Welcome to '.$this->getName().' command</info>');
        $trainingCenter = $this->em->getRepository(TrainingCenter::class)->find($input->getArgument('tcid'));
        if ($trainingCenter) {
            $output->writeln('TrainingCenter ID#'.$input->getArgument('tcid').' '.$trainingCenter->getName().' loaded');
            $output->writeln('<comment>Setting Student relationship</comment>');
            $students = $this->em->getRepository(Student::class)->findAll();
            /** @var Student $student */
            foreach ($students as $student) {
                if ($input->getOption('force')) {
                    $student->setTrainingCenter($trainingCenter);
                }
            }
            $output->writeln('<comment>Setting ClassGroups relationship</comment>');
            $classGroups = $this->em->getRepository(ClassGroup::class)->findAll();
            /** @var ClassGroup $classGroup */
            foreach ($classGroups as $classGroup) {
                if ($input->getOption('force')) {
                    $classGroup->setTrainingCenter($trainingCenter);
                }
            }
            $output->writeln('<comment>Setting Receipts relationship</comment>');
            $receipts = $this->em->getRepository(Receipt::class)->findAll();
            /** @var Receipt $receipt */
            foreach ($receipts as $receipt) {
                if ($input->getOption('force')) {
                    $receipt->setTrainingCenter($trainingCenter);
                }
            }
            $output->writeln('<comment>Setting Invoices relationship</comment>');
            $invoices = $this->em->getRepository(Invoice::class)->findAll();
            /** @var Invoice $invoice */
            foreach ($invoices as $invoice) {
                if ($input->getOption('force')) {
                    $invoice->setTrainingCenter($trainingCenter);
                }
            }
            if ($input->getOption('force')) {
                $this->em->flush();
            }
        } else {
            $output->writeln('<error>No TrainingCenter with ID# '.$input->getArgument('tcid').' found. Nothing done.</error>');
        }
        $output->writeln('<info>EOF.</info>');

        return Command::SUCCESS;
    }
}
