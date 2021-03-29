<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Command\ProductCommandInterface;

class RemoveProductAttributeCommand implements ProductCommandInterface
{
    private ProductId $id;

    private AttributeId $attributeId;

    private Language $language;

    public function __construct(ProductId $id, AttributeId $attributeId, Language $language)
    {
        $this->id = $id;
        $this->attributeId = $attributeId;
        $this->language = $language;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }
}
