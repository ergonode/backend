<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\EventListener;

use Ergonode\Api\Application\Response\ExceptionResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 */
class ExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function __invoke(ExceptionEvent $event): void
    {
        $response = new ExceptionResponse($event->getException());
        $event->setResponse($response);
    }
}
