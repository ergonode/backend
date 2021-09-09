<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Product\Strategy;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractNumericAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class ImportProductNumericAttributeStrategy implements ImportProductAttributeStrategyInterface
{
    public function supported(AttributeType $type): bool
    {
        return AbstractNumericAttribute::TYPE === $type->getValue();
    }

    public function build(AttributeId $id, AttributeCode $code, TranslatableString $value): ValueInterface
    {
        $translation = [];
        foreach ($value as $key => $version) {
            if ('' === $version || null === $version) {
                continue;
            }
            if (!is_numeric($version)) {
                throw new ImportException(
                    '{code} numeric attribute value has to be numeric. `{version}` given',
                    [
                        '{code}' => $code->getValue(),
                        '{version}' => $version,
                    ],
                );
            }
            $translation[$key] = $version;
        }

        return new TranslatableStringValue(new TranslatableString($translation));
    }
}
