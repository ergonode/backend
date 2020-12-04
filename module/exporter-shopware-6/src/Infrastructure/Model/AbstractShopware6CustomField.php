<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldConfig;
use JMS\Serializer\Annotation as JMS;

class AbstractShopware6CustomField
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
     * @JMS\Type("Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomFieldConfig")
     * @JMS\SerializedName("config")
     */
    protected ?AbstractShopware6CustomFieldConfig $config;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("customFieldSetId")
     */
    protected ?string $customFieldSetId;

    /**
     * @JMS\Exclude()
     */
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
}
