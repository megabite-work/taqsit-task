<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\Exception\ValidationFailedException;

// #[AsEventListener(event: 'kernel.exception')]
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $message = $exception->getMessage();
        $response = new Response();
        
        if ($exception instanceof ValidationFailedException || $exception->getPrevious() instanceof ValidationFailedException) {
            $referer = $event->getRequest()->headers->get('referer');
            $event->getRequest()->getSession()->set('data', $event->getRequest()->getPayload()->all());
            $event->getRequest()->getSession()->getFlashBag()->clear();
            $event->getRequest()->getSession()->getFlashBag()->add('error', $message);
            $response = new RedirectResponse($referer);
        }
        
        $event->setResponse($response);
    }
}
