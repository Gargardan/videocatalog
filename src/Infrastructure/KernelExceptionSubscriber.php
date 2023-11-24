<?php

namespace App\Infrastructure;

use App\Domain\Exceptions\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelExceptionSubscriber implements EventSubscriberInterface
{

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException']
        ];
    }

    public function onKernelException(ExceptionEvent $event) : void
    {
        $message = $event->getThrowable()->getMessage();
        $code = $event->getThrowable()->getCode();

        // TODO: Normalizar esto a una clase que se encargue de construir las respuestas
        if (is_a($event->getThrowable(), ValidationException::class)) {
            $responseMessage = "Validation error: $message";
        } else {
            $responseMessage = "Internal error: $message";
            $code = 500;
        }

        $jsonResponse = new JsonResponse(
            [
                'message'   => $responseMessage,
                'code'      => $code
            ],
            $code
        );


        $jsonResponse->send();
    }
}