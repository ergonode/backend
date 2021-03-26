<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Query\Decorator;

use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ramsey\Uuid\Uuid;

class CacheProductQueryDecorator implements ProductQueryInterface
{
    private ProductQueryInterface $query;

    /**
     * @var array
     */
    private array $cache = [];

    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    public function findProductIdBySku(Sku $sku): ?ProductId
    {
        $key = $sku->getValue();
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->query->findProductIdBySku($sku);
        }

        return $this->cache[$key];
    }

    public function findSkuByProductId(ProductId $id): ?Sku
    {
        return $this->query->findSkuByProductId($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getAllIds(): array
    {
        return $this->query->getAllIds();
    }

    /**
     * @return array
     */
    public function getAllEditedIds(?\DateTime $dateTime = null): array
    {
        return $this->query->getAllEditedIds($dateTime);
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
    public function getAllSkus(): array
    {
        return $this->query->getAllSkus();
    }

    /**
     * {@inheritDoc}
     */
    public function getOthersIds(array $productIds): array
    {
        return $this->query->getOthersIds($productIds);
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
     * @return array
     */
    public function findProductIdsByTemplate(TemplateId $templateId): array
    {
        return $this->query->findProductIdsByTemplate($templateId);
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

    /**
     * {@inheritDoc}
     */
    public function findProductIdByOptionId(AggregateId $id)
    {
        return $this->query->findProductIdByOptionId($id);
    }

    /**
     * @return array
     */
    public function getMultimediaRelation(MultimediaId $id): array
    {
        return $this->query->getMultimediaRelation($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findProductIdByType(string $type): array
    {
        return $this->query->findProductIdByType($type);
    }

    /**
     * @return array
     */
    public function autocomplete(
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array {
        return $this->query->autocomplete($search, $limit, $field, $order);
    }

    public function getCount(): int
    {
        return $this->query->getCount();
    }

    public function findAttributeIdsBySku(Sku $sku): array
    {
        return $this->query->findAttributeIdsBySku($sku);
    }

    public function findAttributeIdsByProductId(ProductId $productId): array
    {
        return $this->query->findAttributeIdsByProductId($productId);
    }
}
