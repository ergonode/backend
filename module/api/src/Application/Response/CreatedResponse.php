<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Response;

use Ergonode\SharedKernel\Domain\AbstractCode;
use Symfony\Component\HttpFoundation\Response;
use Ergonode\SharedKernel\Domain\AbstractId;

class CreatedResponse extends AbstractResponse
{
    public function __construct(object $data)
    {
        if ($data instanceof AbstractId || $data instanceof AbstractCode) {
            $data = ['id' => $data->getValue()];
        }

        parent::__construct($data, Response::HTTP_CREATED);
    }
}
