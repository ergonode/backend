<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\EventListener;

use Ergonode\Api\Application\Mapper\ExceptionMapperInterface;
use Ergonode\Api\Application\Response\ExceptionResponse;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class ExceptionListener
{
    private ExceptionMapperInterface $exceptionMapper;
    private ?SerializerInterface $serializer;

    public function __construct(
        ExceptionMapperInterface $exceptionMapper,
        ?SerializerInterface $serializer = null
    ) {
        $this->exceptionMapper = $exceptionMapper;
        $this->serializer = $serializer;
        if (null !== $serializer) {
            return;
        }
        @trigger_error(
            'Not passing the serializer to the constructor of Ergonode\Api\Application\EventListener\ExceptionListener'
                .' is deprecated and will not be supported in 2.0.',
            \E_USER_DEPRECATED,
        );
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        // fix for Messenger exception envelope
        if ($exception instanceof HandlerFailedException) {
            /** @var HandlerFailedException $exception */
            $exception = $exception->getNestedExceptions()[0];
        }

        if (null === $this->serializer) {
            $response = new ExceptionResponse($exception);
        } else {
            $response = $this->mapToResponse($exception);
        }

        $configuration = $this->exceptionMapper->map($exception);
        if (null !== $configuration) {
            $response->setStatusCode($configuration['http']['code']);
        }

        $event->setResponse($response);
    }

    private function mapToResponse(\Throwable $exception): Response
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $headers = [];
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $headers = $exception->getHeaders();
        }

        return new Response(
            $this->serializer->serialize($exception),
            $statusCode,
            $headers,
        );
    }
}
