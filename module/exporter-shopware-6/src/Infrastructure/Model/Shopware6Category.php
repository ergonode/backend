<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

class Shopware6Category implements \JsonSerializable
{
    private ?string $id;

    private ?string $name;

    private ?string $parentId;

    private bool $active;

    private bool $visible;

    private bool $modified = false;

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
        if ($this->name !== $name) {
            $this->name = $name;
            $this->modified = true;
        }
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function setParentId(?string $parentId): void
    {
        if ($this->parentId !== $parentId) {
            $this->parentId = $parentId;
            $this->modified = true;
        }
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        if ($this->active !== $active) {
            $this->active = $active;
            $this->modified = true;
        }
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): void
    {
        if ($this->visible !== $visible) {
            $this->visible = $visible;
            $this->modified = true;
        }
    }

    public function isModified(): bool
    {
        return $this->modified;
    }

    public function jsonSerialize(): array
    {
        $data =
            [
                'name' => $this->name,
                'active' => $this->active,
                'visible' => $this->visible,
            ];
        if (null !== $this->parentId) {
            $data['parentId'] = $this->parentId;
        }

        return $data;
    }
}
