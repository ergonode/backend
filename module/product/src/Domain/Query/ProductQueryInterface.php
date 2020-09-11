<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ramsey\Uuid\Uuid;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

/**
 */
interface ProductQueryInterface
{
    /**
     * @param Language  $language
     * @param ProductId $productId
     *
     * @return DataSetInterface
     */
    public function getDataSetByProduct(Language $language, ProductId $productId): DataSetInterface;

    /**
     * @param Sku $sku
     *
     * @return ProductId|null
     */
    public function findProductIdBySku(Sku $sku): ?ProductId;

    /**
     * @return array
     */
    public function getAllIds(): array;

    /**
     * @param \DateTime|null $dateTime
     *
     * @return array
     */
    public function getAllEditedIds(?\DateTime $dateTime = null): array;

    /**
     * @return array
     */
    public function getAllSkus(): array;

    /**
     * @return array
     */
    public function getDictionary(): array;

    /**
     * @param AttributeId $attributeId
     * @param Uuid|null   $valueId
     *
     * @return array
     */
    public function findProductIdByAttributeId(AttributeId $attributeId, ?Uuid $valueId = null): array;

    /**
     * @param array[] $skus
     *
     * @return ProductId[]
     */
    public function findProductIdsBySkus(array $skus): array;

    /**
     * @param array $segmentIds
     *
     * @return array
     */
    public function findProductIdsBySegments(array $segmentIds): array;

    /**
     * @param ProductId $id
     *
     * @return mixed
     */
    public function findProductCollectionIdByProductId(ProductId $id);

    /**
     * @param AggregateId $id
     *
     * @return mixed
     */
    public function findProductIdByOptionId(AggregateId $id);

    /**
     * @param MultimediaId $id
     *
     * @return array
     */
    public function getMultimediaRelation(MultimediaId $id): array;

    /**
     * @param string $type
     *
     * @return array
     */
    public function findProductIdByType(string $type): array;

    /**
     * @param string|null $search
     * @param int|null    $limit
     * @param string|null $field
     * @param string|null $order
     *
     * @return array
     */
    public function autocomplete(
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array;
}
