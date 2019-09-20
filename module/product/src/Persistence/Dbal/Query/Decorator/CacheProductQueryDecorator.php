<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Query\Decorator;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Workflow\Domain\Entity\StatusId;

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
     * @param Sku $sku
     *
     * @return array|null
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
     * @return array
     */
    public function getAllIds(): array
    {
        return $this->query->getAllIds();
    }

    /**
     * @param CategoryId $categoryId
     *
     * @return ProductId[]
     */
    public function findProductIdByCategoryId(CategoryId $categoryId): array
    {
        return $this->query->findProductIdByCategoryId($categoryId);
    }

    /**
     * @param TemplateId $templateId
     *
     * @return array
     */
    public function findProductIdByTemplateId(TemplateId $templateId): array
    {
        return $this->query->findProductIdByTemplateId($templateId);
    }

    /**
     * @param StatusId $statusId
     *
     * @return array
     */
    public function findProductIdByStatusId(StatusId $statusId): array
    {
        return $this->query->findProductIdByStatusId($statusId);
    }
}
