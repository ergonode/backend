<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

class Shopware6Media implements \JsonSerializable
{
    private ?string $id;

    protected ?string $fileName;

    public function __construct(?string $id, ?string $fileName)
    {
        $this->id = $id;
        $this->fileName = $fileName;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function jsonSerialize(): array
    {
        return ['fileName' => $this->fileName];
    }
}
