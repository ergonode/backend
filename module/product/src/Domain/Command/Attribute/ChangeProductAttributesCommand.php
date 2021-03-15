<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Command\ProductCommandInterface;

class ChangeProductAttributesCommand implements ProductCommandInterface
{
    private ProductId $id;

    private array $attributes;

    public function __construct(ProductId $id, array $attributes)
    {
        $this->id = $id;
        $this->attributes = $attributes;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
