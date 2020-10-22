<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionResponse extends AbstractResponse
{
    /**
     * @param \Throwable $exception
     */
    public function __construct(\Throwable $exception)
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $headers = [];
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $headers = $exception->getHeaders();
        }

        parent::__construct($exception, $statusCode, $headers);
    }
}
