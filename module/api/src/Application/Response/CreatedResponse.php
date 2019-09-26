<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\Response;

use Ergonode\Core\Domain\Entity\AbstractCode;
use Ergonode\Core\Domain\Entity\AbstractId;
use Symfony\Component\HttpFoundation\Response;

/**
 */
class CreatedResponse extends AbstractResponse
{
    /**
     * @param mixed $data
     */
    public function __construct($data)
    {
        if ($data instanceof AbstractId || $data instanceof AbstractCode) {
            $data = ['id' => $data->getValue()];
        }

        parent::__construct($data, Response::HTTP_CREATED);
    }
}
