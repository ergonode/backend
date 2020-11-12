<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use JMS\Serializer\Annotation as JMS;

class Shopware6CustomField
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
     * @JMS\Type("string")
     * @JMS\SerializedName("type")
     */
    protected ?string $type;

    /**
     * @var array|null
     *
     * @JMS\Type("array")
     * @JMS\SerializedName("config")
     */
    protected ?array $config;
    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("customFieldSetId")
     */
    protected ?string $customFieldSetId;

    /**
     * @JMS\Exclude()
     */
    protected bool $modified = false;

    /**
     * @param array|null $config
     */
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $type = null,
        ?array $config = null,
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

    /**
     * @return array
     */
    public function getConfig(): array
    {
        if ($this->config) {
            return $this->config;
        }

        return [];
    }

    public function addConfig(string $key, string $value): void
    {
        if (isset($this->config[$key]) && $this->config[$key] === $value) {
            return;
        }
        $this->config[$key] = $value;
        $this->modified = true;
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

    /**
     * @param array $label
     */
    public function setLabel(array $label): void
    {
        if (isset($this->config['label'])) {
            if (!empty(array_diff($this->config['label'], $label))) {
                $this->config['label'] = $label;
                $this->modified = true;
            }
        } else {
            $this->config['label'] = $label;
            $this->modified = true;
        }
    }

    /**
     * @param array $option
     */
    public function addOptions(array $option): void
    {
        if (isset($this->config['options'])) {
            $this->changeOrCreateOption($option);
        } else {
            $this->config['options'][] = $option;
            $this->modified = true;
        }
    }

    public function isModified(): bool
    {
        return $this->modified;
    }

    /**
     * @param array $option
     */
    private function changeOrCreateOption(array $option): void
    {
        foreach ($this->config['options'] as &$currentOption) {
            if ($currentOption['value'] === $option['value']) {
                if (!empty(array_diff($currentOption['label'], $option['label']))) {
                    $currentOption['label'] = $option['label'];
                    $this->modified = true;
                }

                return;
            }
        }
        $this->config['options'][] = $option;
        $this->modified = true;
    }
}
