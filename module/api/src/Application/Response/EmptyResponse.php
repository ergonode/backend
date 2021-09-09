<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated
 */
class EmptyResponse extends AbstractResponse
{
    /**
     * @param null  $data
     * @param array $headers
     */
    public function __construct($data = null, array $headers = [])
    {
        @trigger_error(
            'Ergonode\Api\Application\Response\CreatedResponse is deprecated and will be removed in 2.0.'
                .' Return `void` or `null` from your controller instead.',
            \E_USER_DEPRECATED,
        );

        parent::__construct($data, Response::HTTP_NO_CONTENT, $headers);
    }
}
