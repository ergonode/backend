<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

interface TemplateQueryInterface
{
    /**
     * @return array
     */
    public function getDictionary(Language $language): array;

    public function getAll(): array;

    public function findTemplateIdByAttributeId(AttributeId $attributeId): array;

    /**
     * @return ProductId[]
     */
    public function findProductIdByTemplateId(TemplateId $templateId): array;

    public function findTemplateIdByCode(string $code): ?TemplateId;

    public function getMultimediaRelation(MultimediaId $id): array;

    /**
     * @param ProductId[] $productIds
     *
     * @return TemplateId[]
     */
    public function findTemplateIdsByProductIds(array $productIds): array;

    public function autocomplete(
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array;
}
