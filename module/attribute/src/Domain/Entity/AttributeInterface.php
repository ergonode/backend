<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Domain\AggregateId;

interface AttributeInterface
{
    public function getType(): string;

    public function getCode(): AttributeCode;

    /**
     * @return AggregateId;
     */
    public function getId(): AggregateId;
}
