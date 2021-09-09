<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Validator\Strategy;

use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\ValueObject\Sku;

interface AttributeImportResolverInterface
{
    public function supported(AttributeType $attributeType): bool;

    public function resolve(Sku $parentSku, TranslatableString $attribute): TranslatableString;
}
