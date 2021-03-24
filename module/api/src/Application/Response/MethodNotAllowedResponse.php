<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MethodNotAllowedResponse extends JsonResponse
{
    /**
     * @param null  $data
     * @param array $headers
     */
    public function __construct($data = null, array $headers = [])
    {
        parent::__construct($data, Response::HTTP_METHOD_NOT_ALLOWED, $headers);
    }
}
