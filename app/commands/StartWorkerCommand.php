<?php

namespace app\commands;


use app\UserProcessor;
use Enqueue\Consumption\ChainExtension;
use Enqueue\Consumption\Extension\SignalExtension;
use Enqueue\Consumption\QueueConsumer;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrProcessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StartWorkerCommand extends Command
{
    /**
     * @var PsrContext
     */
    private $psrContext;
    /**
     * @var UserProcessor
     */
    private $processor;
    /**
     * @var string
     */
    private $queueName;

    /**
     * @param string $name
     * @param PsrContext $psrContext
     * @param PsrProcessor $processor
     * @param string $queueName
     */
    public function __construct(string $name, PsrContext $psrContext, PsrProcessor $processor, string $queueName)
    {
        $this->psrContext = $psrContext;
        $this->processor  = $processor;
        $this->queueName  = $queueName;
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $consumer = new QueueConsumer($this->psrContext, new ChainExtension([
            new SignalExtension()
        ]));
        $consumer->bind($this->queueName, $this->processor)->consume();
    }
}