<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Product\Resolver;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

interface ImportProductAttributeStrategyInterface
{
    public function supported(AttributeType $type): bool;

    public function build(AttributeId $id, AttributeCode $code, TranslatableString $value): ValueInterface;
}
