<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use JMS\Serializer\Annotation as JMS;

/**
 */
class Shopware6Category
{
    /**
     * @var string|null
     *
     * @JMS\Exclude()
     */
    private ?string $id;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    private ?string $name;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("parentId")
     */
    private ?string $parentId;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     * @JMS\SerializedName("active")
     */
    private bool $active;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     * @JMS\SerializedName("visible")
     */
    private bool $visible;

    /**
     * @var bool
     *
     * @JMS\Exclude()
     */
    private bool $modified = false;

    /**
     * @param string|null $id
     * @param string|null $name
     * @param string|null $parentId
     * @param bool        $active
     * @param bool        $visible
     */
    public function __construct(
        ?string $id = null,
        ?string $name = null,
        ?string $parentId = null,
        bool $active = true,
        bool $visible = true
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->parentId = $parentId;
        $this->active = $active;
        $this->visible = $visible;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        if ($this->name !== $name) {
            $this->name = $name;
            $this->modified = true;
        }
    }

    /**
     * @return string|null
     */
    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    /**
     * @param string|null $parentId
     */
    public function setParentId(?string $parentId): void
    {
        if ($this->parentId !== $parentId) {
            $this->parentId = $parentId;
            $this->modified = true;
        }
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        if ($this->active !== $active) {
            $this->active = $active;
            $this->modified = true;
        }
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     */
    public function setVisible(bool $visible): void
    {
        if ($this->visible !== $visible) {
            $this->visible = $visible;
            $this->modified = true;
        }
    }

    /**
     * @return bool
     */
    public function isModified(): bool
    {
        return $this->modified;
    }
}
