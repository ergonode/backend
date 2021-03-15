<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Response;

use Symfony\Component\HttpFoundation\Response;

class SuccessResponse extends AbstractResponse
{
    /**
     * @param null  $data
     * @param array $headers
     */
    public function __construct($data = null, array $headers = [])
    {
        parent::__construct($data, Response::HTTP_OK, $headers);
    }
}
