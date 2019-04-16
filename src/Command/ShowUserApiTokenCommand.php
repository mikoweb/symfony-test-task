<?php

namespace App\Command;

use App\Repository\UserRepository;
use Lcobucci\JWT\Builder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ShowUserApiTokenCommand extends Command
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    protected static $defaultName = 'app:show-user-api-token';

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }


    protected function configure()
    {
        $this
            ->setDescription('Show api token')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $user = $this->userRepository->findUser($input->getArgument('username'));

        if (!is_null($user)) {
            $token = (new Builder())
                ->set('api_key', $user->getApiKey())
                ->getToken()
            ;

            $io->success("Api token - $token");
        }
    }
}
