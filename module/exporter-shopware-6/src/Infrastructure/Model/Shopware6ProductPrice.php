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
class Shopware6ProductPrice
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("currencyId")
     */
    private string $currencyId;

    /**
     * @var float
     *
     * @JMS\Type("float")
     * @JMS\SerializedName("net")
     */
    private float $net;

    /**
     * @var float
     *
     * @JMS\Type("float")
     * @JMS\SerializedName("gross")
     */
    private float $gross;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     * @JMS\SerializedName("linked")
     */
    private bool $linked;

    /**
     * @param string $currencyId
     * @param float  $net
     * @param float  $gross
     * @param bool   $linked
     */
    public function __construct(string $currencyId, float $net, float $gross, bool $linked = false)
    {
        $this->currencyId = $currencyId;
        $this->net = $net;
        $this->gross = $gross;
        $this->linked = $linked;
    }

    /**
     * @return string
     */
    public function getCurrencyId(): string
    {
        return $this->currencyId;
    }

    /**
     * @param string $currencyId
     */
    public function setCurrencyId(string $currencyId): void
    {
        $this->currencyId = $currencyId;
    }

    /**
     * @return float
     */
    public function getNet(): float
    {
        return $this->net;
    }

    /**
     * @param float $net
     */
    public function setNet(float $net): void
    {
        $this->net = $net;
    }

    /**
     * @return float
     */
    public function getGross(): float
    {
        return $this->gross;
    }

    /**
     * @param float $gross
     */
    public function setGross(float $gross): void
    {
        $this->gross = $gross;
    }

    /**
     * @return bool
     */
    public function isLinked(): bool
    {
        return $this->linked;
    }

    /**
     * @param bool $linked
     */
    public function setLinked(bool $linked): void
    {
        $this->linked = $linked;
    }

    /**
     * @param Shopware6ProductPrice $price
     *
     * @return bool
     */
    public function isEqual(Shopware6ProductPrice $price): bool
    {
        return $price->getCurrencyId() === $this->currencyId
            && $price->getNet() === $this->net
            && $price->getGross() === $this->gross;
    }
}
