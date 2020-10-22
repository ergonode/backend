<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Entity;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\SharedKernel\Domain\AggregateId;

interface CategoryInterface
{
    public function getType(): string;

    public function getCode(): CategoryCode;

    /**
     * @return AggregateId;
     */
    public function getId(): AggregateId;
}
