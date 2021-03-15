<?php
/*
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use JMS\Serializer\Annotation as JMS;

abstract class AbstractShopware6CustomFieldConfig
{
    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("type")
     */
    protected ?string $type;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("customFieldType")
     */
    protected ?string $customFieldType;

    /**
     * @JMS\Type("array")
     * @JMS\SerializedName("label")
     */
    protected ?array $label;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("componentName")
     */
    protected ?string $componentName;

    /**
     * @JMS\Exclude()
     */
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
}
