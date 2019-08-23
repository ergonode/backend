<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\Response;

use Ergonode\Core\Domain\Entity\AbstractId;
use Symfony\Component\HttpFoundation\Response;

/**
 */
class CreatedResponse extends AbstractResponse
{
    /**
     * @param AbstractId $id
     */
    public function __construct(AbstractId $id)
    {
        parent::__construct(['id' => $id->getValue()], Response::HTTP_CREATED);
    }
}
