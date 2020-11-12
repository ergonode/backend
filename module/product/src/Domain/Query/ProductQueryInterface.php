<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ramsey\Uuid\Uuid;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

interface ProductQueryInterface
{
    public function getDataSetByProduct(Language $language, ProductId $productId): DataSetInterface;

    public function findProductIdBySku(Sku $sku): ?ProductId;

    /**
     * @return array
     */
    public function getAllIds(): array;

    /**
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
     * @return ProductId[]
     */
    public function findProductIdsByTemplate(TemplateId $templateId): array;

    /**
     * @return mixed
     */
    public function findProductCollectionIdByProductId(ProductId $id);

    /**
     * @return mixed
     */
    public function findProductIdByOptionId(AggregateId $id);

    /**
     * @return array
     */
    public function getMultimediaRelation(MultimediaId $id): array;

    /**
     * @return array
     */
    public function findProductIdByType(string $type): array;

    public function getCount(): int;

    /**
     *
     * @return ProductId[]
     */
    public function findProductIdByCategoryId(CategoryId $categoryId): array;

    /**
     * @return array
     */
    public function autocomplete(
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array;
}
