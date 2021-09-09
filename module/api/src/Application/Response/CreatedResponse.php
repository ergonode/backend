<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Response;

use Ergonode\SharedKernel\Domain\AbstractCode;
use Symfony\Component\HttpFoundation\Response;
use Ergonode\SharedKernel\Domain\AbstractId;

/**
 * @deprecated
 */
class CreatedResponse extends AbstractResponse
{
    public function __construct(object $data)
    {
        @trigger_error(
            'Ergonode\Api\Application\Response\CreatedResponse is deprecated and will be removed in 2.0.'
                .' Return Ergonode\SharedKernel\Domain\AbstractId from your controller instead.',
            \E_USER_DEPRECATED,
        );

        if ($data instanceof AbstractId || $data instanceof AbstractCode) {
            $data = ['id' => $data->getValue()];
        }

        parent::__construct($data, Response::HTTP_CREATED);
    }
}
