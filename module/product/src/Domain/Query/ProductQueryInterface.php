<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ramsey\Uuid\Uuid;

/**
 */
interface ProductQueryInterface
{
    /**
     * @param Sku $sku
     *
     * @return array|null
     */
    public function findBySku(Sku $sku): ?array;

    /**
     * @return array
     */
    public function getAllIds(): array;

    /**
     * @param TemplateId $templateId
     *
     * @return array
     */
    public function findProductIdByTemplateId(TemplateId $templateId): array;

    /**
     * @param AttributeId $attributeId
     * @param Uuid|null   $valueId
     *
     * @return array
     */
    public function findProductIdByAttributeId(AttributeId $attributeId, ?Uuid $valueId = null): array;
}
