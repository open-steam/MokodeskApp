<?php
namespace OpenSteam\MokodeskBundle\EventListener;

use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class BacktraceLoggerListener
{
    private $logger;

    public function __construct(Logger $logger = null)
    {
        $this->logger = $logger;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $this->logger->addError($event->getException());
    }
}


