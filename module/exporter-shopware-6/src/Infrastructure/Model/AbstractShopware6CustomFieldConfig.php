<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

abstract class AbstractShopware6CustomFieldConfig implements \JsonSerializable
{
    protected ?string $type;

    protected ?string $customFieldType;

    protected ?array $label;

    protected ?string $componentName;

    protected bool $modified = false;

    public function __construct(
        ?string $type = null,
        string $customFieldType = null,
        ?array $label = null,
        ?string $componentName = null
    ) {
        $this->type = $type;
        $this->customFieldType = $customFieldType;
        $this->label = $label;
        $this->componentName = $componentName;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        if ($this->type !== $type) {
            $this->type = $type;
            $this->modified = true;
        }
    }

    public function getCustomFieldType(): ?string
    {
        return $this->customFieldType;
    }

    public function setCustomFieldType(?string $customFieldType): void
    {
        if ($this->customFieldType !== $customFieldType) {
            $this->customFieldType = $customFieldType;
            $this->modified = true;
        }
    }

    public function getLabel(): ?array
    {
        return $this->label;
    }

    public function mergeLabel(?array $label): void
    {
        if ($this->label) {
            $newLabel = array_merge($this->label, $label);
            if (!empty(array_diff_assoc($this->label, $newLabel))) {
                $this->label = $newLabel;
                $this->modified = true;
            }
        } else {
            $this->label = $label;
            $this->modified = true;
        }
    }

    public function getComponentName(): ?string
    {
        return $this->componentName;
    }

    public function setComponentName(string $componentName): void
    {
        if ($this->componentName !== $componentName) {
            $this->componentName = $componentName;
            $this->modified = true;
        }
    }

    public function isModified(): bool
    {
        return $this->modified;
    }

    public function jsonSerialize(): array
    {
        $data = [
            'type' => $this->type,
            'customFieldType' => $this->customFieldType,
        ];
        if ($this->label) {
            $data['label'] = $this->label;
        }
        if (null !== $this->componentName) {
            $data['componentName'] = $this->componentName;
        }

        return $data;
    }
}
