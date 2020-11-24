<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model\Basic;

use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomFieldConfig;
use JMS\Serializer\Annotation as JMS;

class Shopware6CustomFieldConfig extends AbstractShopware6CustomFieldConfig
{
    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("dateType")
     */
    protected ?string $dateType;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("numberType")
     */
    protected ?string $numberType;

    /**
     * @JMS\Type("array")
     * @JMS\SerializedName("options")
     */
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

    public function setDateType(?string $dateType): void
    {
        if ($this->dateType !== $dateType) {
            $this->dateType = $dateType;
            $this->modified = true;
        }
    }

    public function setNumberType(?string $numberType): void
    {
        if ($this->numberType !== $numberType) {
            $this->numberType = $numberType;
            $this->modified = true;
        }
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

    private function changeOrCreateOption(array $option): void
    {
        foreach ($this->options as &$currentOption) {
            if ($currentOption['value'] === $option['value']) {
                if (!empty(array_diff($currentOption['label'], $option['label']))) {
                    $currentOption['label'] = $option['label'];
                    $this->modified = true;
                }

                return;
            }
        }
        $this->options[] = $option;
        $this->modified = true;
    }
}
