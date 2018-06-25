<?php

namespace app\commands;


use app\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserListCommand extends Command
{
    /**
     * @var Storage
     */
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this->setName('user:list');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = $this->storage->getUsers();

        array_walk($users, function (array $user) use ($output) {
            $albumCount = $this->storage->getAlbumCount($user['vk_user_id']);
            $message = sprintf('id: %d, name: %s, total albums: %d',
                $user['vk_user_id'], $user['last_name'] . ' ' . $user['first_name'], $albumCount);
            $output->writeln($message);
        });
    }
}