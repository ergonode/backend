<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Validator;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Infrastructure\Validator\Strategy\AttributeRedispatchImportValidatorStrategyInterface;
use Webmozart\Assert\Assert;

class AttributeToRedispatchImportValidator
{
    /**
     * @var AttributeRedispatchImportValidatorStrategyInterface[]
     */
    private iterable $strategies;

    public function __construct(iterable $strategies)
    {
        Assert::allIsInstanceOf($strategies, AttributeRedispatchImportValidatorStrategyInterface::class);

        $this->strategies = $strategies;
    }

    public function validate(
        AttributeType $attributeType,
        AttributeCode $attributeCode,
        TranslatableString $attribute
    ): bool {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supported($attributeType)) {
                return $strategy->validate($attributeCode, $attribute);
            }
        }

        return true;
    }
}
