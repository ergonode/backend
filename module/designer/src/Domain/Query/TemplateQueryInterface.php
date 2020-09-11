<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Designer\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

/**
 */
interface TemplateQueryInterface
{
    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getDictionary(Language $language): array;

    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param AttributeId $attributeId
     *
     * @return array
     */
    public function findTemplateIdByAttributeId(AttributeId $attributeId): array;

    /**
     * @param TemplateId $templateId
     *
     * @return ProductId[]
     */
    public function findProductIdByTemplateId(TemplateId $templateId): array;

    /**
     * @param ProductId $productId
     *
     * @return TemplateId
     */
    public function findProductTemplateId(ProductId $productId): TemplateId;

    /**
     * @param string $code
     *
     * @return TemplateId|null
     */
    public function findTemplateIdByCode(string $code): ?TemplateId;

    /**
     * @param MultimediaId $id
     *
     * @return array
     */
    public function getMultimediaRelation(MultimediaId $id): array;

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
