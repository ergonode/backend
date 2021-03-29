<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model\Basic;

use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomFieldConfig;

class Shopware6CustomFieldConfig extends AbstractShopware6CustomFieldConfig
{
    protected ?string $dateType;

    protected ?string $numberType;

    protected ?array $options;

    public function __construct(
        ?string $type = null,
        string $customFieldType = null,
        ?array $label = null,
        ?string $componentName = null,
        ?string $dateType = null,
        ?string $numberType = null,
        ?array $options = null
    ) {
        parent::__construct($type, $customFieldType, $label, $componentName);
        $this->dateType = $dateType;
        $this->numberType = $numberType;
        $this->options = $options;
    }

    public function getDateType(): ?string
    {
        return $this->dateType;
    }

    public function setDateType(?string $dateType): void
    {
        if ($this->dateType !== $dateType) {
            $this->dateType = $dateType;
            $this->modified = true;
        }
    }

    public function getNumberType(): ?string
    {
        return $this->numberType;
    }

    public function setNumberType(?string $numberType): void
    {
        if ($this->numberType !== $numberType) {
            $this->numberType = $numberType;
            $this->modified = true;
        }
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function addOptions(array $option): void
    {
        if (!is_array($this->options)) {
            $this->options = [];
            $this->options[] = $option;
            $this->modified = true;
        } else {
            $this->changeOrCreateOption($option);
        }
    }

    public function jsonSerialize(): array
    {
        $data = parent::jsonSerialize();
        if (null !== $this->dateType) {
            $data['dateType'] = $this->dateType;
        }
        if (null !== $this->numberType) {
            $data['numberType'] = $this->numberType;
        }
        if ($this->options) {
            $data['options'] = $this->options;
        }

        return $data;
    }

    private function changeOrCreateOption(array $option): void
    {
        foreach ($this->options as &$currentOption) {
            if ($currentOption['value'] === $option['value']) {
                $newLabel = array_merge($currentOption['label'], $option['label']);
                if (!empty(array_diff_assoc($currentOption['label'], $newLabel))) {
                    $currentOption['label'] = $newLabel;
                    $this->modified = true;
                }

                return;
            }
        }
        $this->options[] = $option;
        $this->modified = true;
    }
}
