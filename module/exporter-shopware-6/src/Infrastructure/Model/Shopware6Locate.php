<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

class Shopware6Locate
{
    private ?string $id;

    private ?string $code;

    private ?string $name;

    public function __construct(?string $id = null, ?string $code = null, ?string $name = null)
    {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
