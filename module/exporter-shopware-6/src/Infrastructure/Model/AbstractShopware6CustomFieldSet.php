<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

abstract class AbstractShopware6CustomFieldSet implements \JsonSerializable
{
    protected ?string $id;

    protected ?string $name;

    protected ?AbstractShopware6CustomFieldSetConfig $config;

    protected ?array $relations;

    protected bool $modified = false;

    /**
     * @param array|null $relations
     */
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?AbstractShopware6CustomFieldSetConfig $config = null,
        ?array $relations = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->config = $config;
        $this->relations = $relations;
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

    public function getConfig(): ?AbstractShopware6CustomFieldSetConfig
    {
        return $this->config;
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

    public function isModified(): bool
    {
        return $this->modified;
    }

    public function jsonSerialize(): array
    {
        $data = [
            'name' => $this->name,
        ];

        if (null !== $this->config) {
            $data['config'] = $this->config->jsonSerialize();
        }
        if (null !== $this->relations) {
            $data['relations'] = $this->relations;
        }

        return $data;
    }
}
