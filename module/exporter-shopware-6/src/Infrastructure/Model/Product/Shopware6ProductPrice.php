<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model\Product;

class Shopware6ProductPrice implements \JsonSerializable
{
    private string $currencyId;

    private float $net;

    private float $gross;

    private bool $linked;

    public function __construct(string $currencyId, float $net, float $gross, bool $linked = false)
    {
        $this->currencyId = $currencyId;
        $this->net = $net;
        $this->gross = $gross;
        $this->linked = $linked;
    }

    public function getCurrencyId(): string
    {
        return $this->currencyId;
    }

    public function setCurrencyId(string $currencyId): void
    {
        $this->currencyId = $currencyId;
    }

    public function getNet(): float
    {
        return $this->net;
    }

    public function setNet(float $net): void
    {
        $this->net = $net;
    }

    public function getGross(): float
    {
        return $this->gross;
    }

    public function setGross(float $gross): void
    {
        $this->gross = $gross;
    }

    public function isLinked(): bool
    {
        return $this->linked;
    }

    public function setLinked(bool $linked): void
    {
        $this->linked = $linked;
    }

    public function isEqual(Shopware6ProductPrice $price): bool
    {
        return $price->getCurrencyId() === $this->currencyId
            && $price->getNet() === $this->net
            && $price->getGross() === $this->gross;
    }

    public function jsonSerialize(): array
    {
        return [
            'currencyId' => $this->currencyId,
            'net' => $this->net,
            'gross' => $this->gross,
            'linked' => $this->linked,
        ];
    }
}
