<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Mapper\Strategy;

use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

interface AttributeMapperStrategyInterface
{
    public function supported(AttributeType $type): bool;

    public function map(array $values): ValueInterface;
}
