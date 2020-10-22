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

interface TemplateQueryInterface
{
    public function getDataSet(): DataSetInterface;

    /**
     * @return array
     */
    public function getDictionary(Language $language): array;

    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @return array
     */
    public function findTemplateIdByAttributeId(AttributeId $attributeId): array;

    /**
     * @return ProductId[]
     */
    public function findProductIdByTemplateId(TemplateId $templateId): array;

    public function findProductTemplateId(ProductId $productId): TemplateId;

    public function findTemplateIdByCode(string $code): ?TemplateId;

    /**
     * @return array
     */
    public function getMultimediaRelation(MultimediaId $id): array;

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
