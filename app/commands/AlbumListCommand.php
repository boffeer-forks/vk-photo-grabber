<?php

namespace app\commands;


use app\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AlbumListCommand extends Command
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
        $this
            ->setName('album:list')
            ->addArgument('vk_user_id', InputArgument::REQUIRED, 'VK id of the album owner.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $vkUserId = $input->getArgument('vk_user_id');
        $albums = $this->storage->getUserAlbums($vkUserId);

        array_walk($albums, function (array $album) use ($output) {
            $albumSize = $this->storage->getAlbumSize($album['vk_album_id']);
            $message = sprintf('id: %d, title: %s, total photos: %d',
                $album['vk_album_id'], $album['title'], $albumSize);
            $output->writeln($message);
        });
    }
}