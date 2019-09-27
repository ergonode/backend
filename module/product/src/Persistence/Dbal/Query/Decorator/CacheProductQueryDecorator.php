<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Query\Decorator;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;

/**
 */
class CacheProductQueryDecorator implements ProductQueryInterface
{
    /**
     * @var ProductQueryInterface
     */
    private $query;

    /**
     * @var array
     */
    private $cache = [];

    /**
     * @param ProductQueryInterface $query
     */
    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function findBySku(Sku $sku): ?array
    {
        $key = $sku->getValue();
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->query->findBySku($sku);
        }

        return $this->cache[$key];
    }

    /**
     * {@inheritDoc}
     */
    public function getAllIds(): array
    {
        return $this->query->getAllIds();
    }

    /**
     * {@inheritDoc}
     */
    public function findProductIdByCategoryId(CategoryId $categoryId): array
    {
        return $this->query->findProductIdByCategoryId($categoryId);
    }

    /**
     * {@inheritDoc}
     */
    public function findProductIdByTemplateId(TemplateId $templateId): array
    {
        return $this->query->findProductIdByTemplateId($templateId);
    }

    /**
     * {@inheritDoc}
     */
    public function findProductIdByAttributeId(AttributeId $attributeId): array
    {
        return $this->query->findProductIdByAttributeId($attributeId);
    }
}
