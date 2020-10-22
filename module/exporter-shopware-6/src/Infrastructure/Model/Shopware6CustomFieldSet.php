<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use JMS\Serializer\Annotation as JMS;

class Shopware6CustomFieldSet
{
    /**
     * @JMS\Exclude()
     */
    protected ?string $id;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    protected ?string $name;

    /**
     * @var array
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("relations")
     */
    protected ?array $relations;

    /**
     * @var array
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("customFields")
     */
    protected ?array $customFields;

    /**
     * @JMS\Exclude()
     */
    protected bool $modified = false;

    /**
     * @param array|null $relations
     * @param array|null $customFields
     */
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?array $relations = null,
        ?array $customFields = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->relations = $relations;
        $this->customFields = $customFields;
    }


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        if ($name !== $this->name) {
            $this->name = $name;
            $this->modified = true;
        }
    }

    /**
     * @return array
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * @param array $relations
     */
    public function addRelation(array $relations): void
    {
        $this->relations[] = $relations;
    }

    /**
     * @return array
     */
    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    /**
     * @param array $customField
     */
    public function addCustomField(array $customField): void
    {
        $this->customFields[] = $customField;
    }

    public function isModified(): bool
    {
        return $this->modified;
    }
}
