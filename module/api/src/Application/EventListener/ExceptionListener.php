<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\EventListener;

use Ergonode\Api\Application\Mapper\ExceptionResponseMapper;
use Ergonode\Api\Application\Response\ExceptionResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 */
class ExceptionListener
{
    /**
     * @var ExceptionResponseMapper
     */
    private $exceptionResponseMapper;

    /**
     * @param ExceptionResponseMapper $exceptionResponseMapper
     */
    public function __construct(ExceptionResponseMapper $exceptionResponseMapper)
    {
        $this->exceptionResponseMapper = $exceptionResponseMapper;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getException();
        $response = new ExceptionResponse($exception);

        $data = $this->exceptionResponseMapper->map($exception);
        if (null !== $data) {
            $response->setStatusCode($data['http']['code']);
        }

        $event->setResponse($response);
    }
}
