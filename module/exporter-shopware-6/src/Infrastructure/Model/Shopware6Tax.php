<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use JMS\Serializer\Annotation as JMS;

class Shopware6Tax
{
    /**
     * @JMS\Exclude()
     */
    protected ?string $id;

    /**
     * @JMS\Type("float")
     * @JMS\SerializedName("taxRate")
     */
    private ?float $rate;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     */
    private ?string $name;

    public function __construct(?string $id = null, ?float $rate = null, ?string $name = null)
    {
        $this->id = $id;
        $this->rate = $rate;
        $this->name = $name;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
