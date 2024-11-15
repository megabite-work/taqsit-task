<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

#[AsEventListener(event: 'kernel.exception')]
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $message = $exception->getMessage();
        $response = new Response();
        
        if ($exception instanceof HttpExceptionInterface) {
            $referer = $event->getRequest()->headers->get('referer');
            $event->getRequest()->getSession()->set('data', $event->getRequest()->getPayload()->all());
            $event->getRequest()->getSession()->getFlashBag()->add('danger', $message);
            $response = new RedirectResponse($referer);
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}
