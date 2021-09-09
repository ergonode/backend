<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated
 */
class MethodNotAllowedResponse extends JsonResponse
{
    /**
     * @param null  $data
     * @param array $headers
     */
    public function __construct($data = null, array $headers = [])
    {
        @trigger_error(
            'Ergonode\Api\Application\Response\MethodNotAllowedResponse is deprecated and will be removed in 2.0.'
            .' Use Symfony\Component\HttpFoundation\Response instead.',
            \E_USER_DEPRECATED,
        );

        parent::__construct($data, Response::HTTP_METHOD_NOT_ALLOWED, $headers);
    }
}
