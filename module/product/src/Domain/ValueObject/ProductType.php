<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\ValueObject;

use Ergonode\Product\Domain\Entity\GroupingProduct;
use Ergonode\Product\Domain\Entity\ProductWithVariants;
use Ergonode\Product\Domain\Entity\SimpleProduct;

/**
 */
class ProductType
{
    public const AVAILABLE = [
        SimpleProduct::TYPE,
        ProductWithVariants::TYPE,
        GroupingProduct::TYPE,
    ];

    /**
     * @var string
     */
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = strtoupper(trim($value));
        if (!self::isValid($this->value)) {
            throw new \InvalidArgumentException(\sprintf('Code "%s" is not valid product type', $value));
        }
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }


    /**
     * @param string $code
     *
     * @return ProductType
     */
    public static function fromString(string $code): self
    {
        return new self($code);
    }

    /**
     * @param ProductType $language
     *
     * @return bool
     */
    public function isEqual(ProductType $language): bool
    {
        return $language->value === $this->value;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public static function isValid(?string $code): bool
    {
        return \in_array($code, self::AVAILABLE, true);
    }
}
