<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Resolver;

use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Infrastructure\Validator\Strategy\AttributeImportResolverInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Webmozart\Assert\Assert;

class AttributeImportResolver
{

    /**
     * @var AttributeImportResolverInterface[]
     */
    private iterable $strategies;

    public function __construct(iterable $strategies)
    {
        Assert::allIsInstanceOf($strategies, AttributeImportResolverInterface::class);

        $this->strategies = $strategies;
    }

    public function resolve(
        AttributeType $attributeType,
        Sku $sku,
        TranslatableString $attribute
    ): TranslatableString {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supported($attributeType)) {
                return $strategy->resolve($sku, $attribute);
            }
        }

        return $attribute;
    }
}
