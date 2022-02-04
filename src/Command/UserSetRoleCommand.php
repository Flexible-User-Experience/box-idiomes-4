<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserSetRoleCommand extends Command
{
    private EntityManagerInterface $em;
    protected static $defaultDescription = 'Apply new role to the user';

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:user:set-role')
            ->addArgument('username', InputArgument::REQUIRED, 'User identifier')
            ->addArgument('role', InputArgument::REQUIRED, 'User new role')
            ->setHelp('Previously user roles stored will be destroyed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Welcome to '.$this->getName().' command');
        $username = $input->getArgument('username');
        $role = $input->getArgument('role');
        /** @var User $user */
        $user = $this->em->getRepository(User::class)->findOneBy([
            'username' => $username,
        ]);
        if (!$user) {
            $io->error(sprintf('User %s not forund', $username));

            return Command::FAILURE;
        }
        $user->setRoles([strtoupper($role)]);
        $this->em->flush();
        $io->success(strtoupper($role).' has been applied to '.$username.' user');

        return Command::SUCCESS;
    }
}
