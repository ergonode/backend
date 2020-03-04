<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Query\Decorator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ramsey\Uuid\Uuid;

/**
 */
class CacheProductQueryDecorator implements ProductQueryInterface
{
    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $query;

    /**
     * @var array
     */
    private array $cache = [];

    /**
     * @param ProductQueryInterface $query
     */
    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param Language  $language
     * @param ProductId $productId
     *
     * @return DataSetInterface
     */
    public function getDataSetByProduct(Language $language, ProductId $productId): DataSetInterface
    {
        return $this->query->getDataSetByProduct($language, $productId);
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
    public function getAllIds(): ?array
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
    public function getAllSkus(): ?array
    {
        return $this->query->getAllSkus();
    }

    /**
     * {@inheritDoc}
     */
    public function getDictionary(): array
    {
        return $this->query->getDictionary();
    }

    /**
     * {@inheritDoc}
     */
    public function findProductIdByAttributeId(AttributeId $attributeId, ?Uuid $valueId = null): array
    {
        return $this->query->findProductIdByAttributeId($attributeId, $valueId);
    }

    /**
     * {@inheritDoc}
     */
    public function findProductIdsBySkus(array $skus): array
    {
        return $this->query->findProductIdsBySkus($skus);
    }

    /**
     * {@inheritDoc}
     */
    public function findProductIdsBySegments(array $segmentIds): array
    {
        return $this->query->findProductIdsBySegments($segmentIds);
    }
}
