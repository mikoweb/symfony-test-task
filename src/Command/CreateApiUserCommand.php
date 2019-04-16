<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Builder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateApiUserCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    protected static $defaultName = 'app:create-api-user';

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create api user')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('apikey', InputArgument::REQUIRED, 'Api key')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');
        $apiKey = $input->getArgument('apikey');

        $user = new User();
        $user->setUsername($username);
        $user->setApiKey($apiKey);
        $user->setRoles(['ROLE_API_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $token = (new Builder())
            ->set('api_key', $user->getApiKey())
            ->getToken()
        ;

        $io->success("Api token - $token");
    }
}
