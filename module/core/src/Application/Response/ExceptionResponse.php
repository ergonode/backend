<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 */
class ExceptionResponse extends AbstractResponse
{
    /**
     * @param \Exception $exception
     * @param array      $headers
     */
    public function __construct(\Exception $exception, array $headers = [])
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $headers = $exception->getHeaders();
        } elseif ($exception instanceof AccessDeniedException) {
            $statusCode = Response::HTTP_FORBIDDEN;
        }

        parent::__construct($exception, $statusCode, $headers);
    }
}
