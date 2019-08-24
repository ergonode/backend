<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\EventListener;

use Ergonode\Api\Application\Mapper\ExceptionMapperInterface;
use Ergonode\Api\Application\Response\ExceptionResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 */
class ExceptionListener
{
    /**
     * @var ExceptionMapperInterface
     */
    private $exceptionMapper;

    /**
     * @param ExceptionMapperInterface $exceptionMapper
     */
    public function __construct(ExceptionMapperInterface $exceptionMapper)
    {
        $this->exceptionMapper = $exceptionMapper;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getException();
        $response = new ExceptionResponse($exception);

        $configuration = $this->exceptionMapper->map($exception);
        if (null !== $configuration) {
            $response->setStatusCode($configuration['http']['code']);
        }

        $event->setResponse($response);
    }
}
