<?php

namespace app\commands;


use app\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AlbumPhotoListCommand extends Command
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
            ->setName('photo:list')
            ->addArgument('vk_album_id', InputArgument::REQUIRED, 'VK id of the album.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $vkAlbumId = $input->getArgument('vk_album_id');
        $photos = $this->storage->getPhotosFromAlbum($vkAlbumId);

        array_walk($photos, function (array $photo) use ($output) {
            $message = sprintf('id: %d, url: %s', $photo['vk_photo_id'], $photo['vk_url']);
            $output->writeln($message);
        });
    }
}