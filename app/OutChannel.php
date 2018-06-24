<?php

namespace app;


use Interop\Queue\PsrContext;
use Interop\Queue\PsrDestination;

class OutChannel
{
    /**
     * @var PsrContext
     */
    private $psrContext;
    /**
     * @var PsrDestination
     */
    private $destination;

    /**
     * @param PsrContext $psrContext
     * @param PsrDestination $destination
     */
    public function __construct(PsrContext $psrContext, PsrDestination $destination)
    {
        $this->psrContext  = $psrContext;
        $this->destination = $destination;
    }

    /**
     * @param string $body
     * @throws \Interop\Queue\Exception
     * @throws \Interop\Queue\InvalidDestinationException
     * @throws \Interop\Queue\InvalidMessageException
     */
    public function send(string $body)
    {
        $message = $this->psrContext->createMessage($body);
        $this->psrContext->createProducer()->send($this->destination, $message);
    }
}