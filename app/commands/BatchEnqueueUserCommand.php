<?php

namespace app\commands;


use app\CsvIterator;
use app\OutChannel;
use Enqueue\Util\JSON;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BatchEnqueueUserCommand extends Command
{
    /**
     * @var OutChannel
     */
    private $userQueue;

    /**
     * @param OutChannel $userQueue
     */
    public function __construct(OutChannel $userQueue)
    {
        $this->userQueue = $userQueue;
        parent::__construct(null);
    }

    protected function configure()
    {
        $this
            ->setName('queue:batch-enqueue-user')
            ->addArgument('file', InputArgument::REQUIRED, 'Location of the CSV file.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $usersFilename = $input->getArgument('file');
        $iterator = new CsvIterator();
        foreach ($iterator->readColumn($usersFilename) as $vkUserId) {
            $this->userQueue->send(JSON::encode($vkUserId));
        }
    }
}