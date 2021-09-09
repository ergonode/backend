<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * @deprecated
 */
class ExceptionResponse extends AbstractResponse
{
    public function __construct(\Throwable $exception)
    {
        @trigger_error(
            'Ergonode\Api\Application\Response\ExceptionResponse is deprecated and will be removed in 2.0.'
            .' Throw appropriate exception instead.',
            \E_USER_DEPRECATED,
        );

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $headers = [];
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $headers = $exception->getHeaders();
        }

        parent::__construct($exception, $statusCode, $headers);
    }
}
