<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Query;

use Ergonode\Transformer\Domain\Entity\TransformerId;

/**
 */
interface ProcessorQueryInterface
{
    /**
     * @param TransformerId $transformerId
     *
     * @return array
     */
    public function findProcessorIdByTransformerId(TransformerId $transformerId): array;
}
