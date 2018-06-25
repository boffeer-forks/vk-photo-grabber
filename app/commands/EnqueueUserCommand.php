<?php

namespace app\commands;


use app\OutChannel;
use Enqueue\Util\JSON;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnqueueUserCommand extends Command
{
    /**
     * @var OutChannel
     */
    private $userQueue;

    protected function configure()
    {
        $this
            ->setName('queue:enqueue-user')
            ->addArgument('vk_user_id', InputArgument::REQUIRED, 'VK id of the user.')
        ;
    }

    /**
     * @param OutChannel $userQueue
     */
    public function __construct(OutChannel $userQueue)
    {
        $this->userQueue = $userQueue;
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $vkUserId = $input->getArgument('vk_user_id');
        $this->userQueue->send(JSON::encode($vkUserId));
    }
}