<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Validator;

use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Infrastructure\Validator\Strategy\AttributeImportValidatorStrategyInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Webmozart\Assert\Assert;

class AttributeImportValidator
{

    /**
     * @var AttributeImportValidatorStrategyInterface[]
     */
    private iterable $strategies;

    public function __construct(iterable $strategies)
    {
        Assert::allIsInstanceOf($strategies, AttributeImportValidatorStrategyInterface::class);

        $this->strategies = $strategies;
    }

    public function validate(
        AttributeType $attributeType,
        Sku $sku,
        TranslatableString $attribute
    ): TranslatableString {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supported($attributeType)) {
                return $strategy->validate($sku, $attribute);
            }
        }

        return $attribute;
    }
}
