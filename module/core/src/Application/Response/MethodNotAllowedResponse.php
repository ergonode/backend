<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 */
class MethodNotAllowedResponse extends JsonResponse
{
    /**
     * @param null  $data
     * @param array $headers
     * @param bool  $json
     */
    public function __construct($data = null, array $headers = [], bool $json = false)
    {
        parent::__construct($data, Response::HTTP_METHOD_NOT_ALLOWED, $headers, $json);
    }
}
