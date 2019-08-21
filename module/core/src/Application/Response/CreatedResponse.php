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
class CreatedResponse extends JsonResponse
{
    /**
     * @param string $id
     */
    public function __construct(string $id)
    {
        parent::__construct(['id' => $id], Response::HTTP_CREATED);
    }
}
