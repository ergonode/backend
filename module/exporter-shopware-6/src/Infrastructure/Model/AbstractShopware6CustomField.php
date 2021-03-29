<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldConfig;

class AbstractShopware6CustomField implements \JsonSerializable
{
    protected ?string $id;

    protected ?string $name;

    protected ?string $type;

    protected ?AbstractShopware6CustomFieldConfig $config;

    protected ?string $customFieldSetId;

    protected bool $modified = false;

    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $type = null,
        ?AbstractShopware6CustomFieldConfig $config = null,
        ?string $customFieldSetId = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->config = $config;
        $this->customFieldSetId = $customFieldSetId;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        if ($name !== $this->name) {
            $this->name = $name;
            $this->modified = true;
        }
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        if ($type !== $this->type) {
            $this->type = $type;
            $this->modified = true;
        }
    }

    public function getConfig(): AbstractShopware6CustomFieldConfig
    {
        if (null === $this->config) {
            $this->config = new Shopware6CustomFieldConfig();
        }

        return $this->config;
    }

    public function getCustomFieldSetId(): ?string
    {
        return $this->customFieldSetId;
    }

    public function setCustomFieldSetId(?string $customFieldSetId): void
    {
        if ($customFieldSetId !== $this->customFieldSetId) {
            $this->customFieldSetId = $customFieldSetId;
            $this->modified = true;
        }
    }

    public function isModified(): bool
    {
        return $this->modified || $this->getConfig()->isModified();
    }

    public function jsonSerialize(): array
    {
        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'customFieldSetId' => $this->customFieldSetId,
        ];
        if (null !== $this->config) {
            $data['config'] = $this->config->jsonSerialize();
        }

        return $data;
    }
}
