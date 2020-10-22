<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Product\Resolver;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

interface ImportProductAttributeStrategyInterface
{
    /**
     * @param AttributeType $type
     *
     * @return bool
     */
    public function supported(AttributeType $type): bool;

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $value
     *
     * @return ValueInterface
     */
    public function build(AttributeId $id, AttributeCode $code, TranslatableString $value): ValueInterface;
}
