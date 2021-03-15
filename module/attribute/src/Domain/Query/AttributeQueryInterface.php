<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Query;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Attribute\Domain\View\AttributeViewModel;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;

interface AttributeQueryInterface
{
    public function checkAttributeExistsByCode(AttributeCode $code): bool;

    public function findAttributeByCode(AttributeCode $code): ?AttributeViewModel;

    public function findAttributeIdByCode(AttributeCode $code): ?AttributeId;

    public function findAttributeCodeById(AttributeId $id): ?AttributeCode;

    public function findAttributeType(AttributeId $id): ?AttributeType;

    public function findAttributeScope(AttributeId $id): ?AttributeScope;

    /**
     * @return array|null
     */
    public function getAttribute(AttributeId $attributeId): ?array;

    /**
     * @return string[]
     */
    public function getAllAttributeCodes(): array;



    /**
     * @param array $types
     *
     * @return string[]
     */
    public function getDictionary(array $types = []): array;

    /**
     * @param string[] $types
     * @return string[]
     */
    public function getAttributeCodes(array $types = [], bool $includeSystem = true): array;

    public function findAttributeOption(AttributeId $id, OptionKey $key): ?OptionInterface;

    /**
     * @return array
     */
    public function findAttributeIdsByUnitId(UnitId $id): array;

    /**
     * @return array
     */
    public function findAttributeIdsByAttributeGroupId(AttributeGroupId $id): array;

    /**
     * @return array
     */
    public function getMultimediaRelation(MultimediaId $id): array;

    /**
     * @return array
     */
    public function autocomplete(
        Language $language,
        string $search = null,
        string $type = null,
        int $limit = null,
        string $field = null,
        string $system = null,
        ?string $order = 'ASC'
    ): array;
}
