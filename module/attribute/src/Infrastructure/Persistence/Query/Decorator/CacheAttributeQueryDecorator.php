<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Query\Decorator;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Attribute\Domain\View\AttributeViewModel;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;

class CacheAttributeQueryDecorator implements AttributeQueryInterface
{
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var array
     */
    private array $cache = [];

    public function __construct(AttributeQueryInterface $attributeQuery)
    {
        $this->attributeQuery = $attributeQuery;
    }

    public function checkAttributeExistsByCode(AttributeCode $code): bool
    {
        return $this->attributeQuery->checkAttributeExistsByCode($code);
    }

    public function findAttributeByCode(AttributeCode $code): ?AttributeViewModel
    {
        $key = $code->getValue();
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->attributeQuery->findAttributeByCode($code);
        }

        return $this->cache[$key];
    }

    public function findAttributeType(AttributeId $id): ?AttributeType
    {
        $key = sprintf('type_%s', $id->getValue());
        if (!array_key_exists($key, $this->cache)) {
            $this->cache[$key] = $this->attributeQuery->findAttributeType($id);
        }

        return $this->cache[$key];
    }

    public function findAttributeIdByCode(AttributeCode $code): ?AttributeId
    {
        return $this->attributeQuery->findAttributeIdByCode($code);
    }

    public function findAttributeScope(AttributeId $id): ?AttributeScope
    {
        return $this->attributeQuery->findAttributeScope($id);
    }

    /**
     * @return array|null
     */
    public function getAttribute(AttributeId $attributeId): ?array
    {
        $key = $attributeId->getValue();
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = $this->attributeQuery->getAttribute($attributeId);
        }

        return $this->cache[$key];
    }

    /**
     * @return array
     */
    public function getAllAttributeCodes(): array
    {
        return $this->attributeQuery->getAllAttributeCodes();
    }

    /**
     * @param array $types
     *
     * @return array
     */
    public function getAttributeCodes(array $types = [], bool $includeSystem = true): array
    {
        return $this->attributeQuery->getAttributeCodes($types, $includeSystem);
    }

    public function findAttributeOption(AttributeId $id, OptionKey $key): ?OptionInterface
    {
        return $this->attributeQuery->findAttributeOption($id, $key);
    }

    /**
     * @param array $types
     *
     * @return array
     */
    public function getDictionary(array $types = []): array
    {
        return $this->attributeQuery->getDictionary($types);
    }

    /**
     * @return array
     */
    public function findAttributeIdsByUnitId(UnitId $id): array
    {
        return $this->attributeQuery->findAttributeIdsByUnitId($id);
    }

    /**
     * @return array
     */
    public function findAttributeIdsByAttributeGroupId(AttributeGroupId $id): array
    {
        return $this->attributeQuery->findAttributeIdsByAttributeGroupId($id);
    }

    /**
     * @return array
     */
    public function getMultimediaRelation(MultimediaId $id): array
    {
        return $this->attributeQuery->getMultimediaRelation($id);
    }

    public function findAttributeCodeById(AttributeId $id): ?AttributeCode
    {
        $key = sprintf('id_%s', $id->getValue());
        if (!array_key_exists($key, $this->cache)) {
            $this->cache[$key] = $this->attributeQuery->findAttributeCodeById($id);
        }

        return $this->cache[$key];
    }

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
    ): array {
        return $this->attributeQuery->autocomplete($language, $search, $type, $limit, $field, $system, $order);
    }
}
