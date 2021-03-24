<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model\Product;

use JMS\Serializer\Annotation as JMS;

class Shopware6ProductConfiguratorSettings
{
    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("id")
     */
    private ?string $id;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("optionId")
     */
    private ?string $optionId;

    public function __construct(?string $id = null, ?string $optionId = null)
    {
        $this->id = $id;
        $this->optionId = $optionId;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getOptionId(): ?string
    {
        return $this->optionId;
    }

    public function setOptionId(?string $optionId): void
    {
        $this->optionId = $optionId;
    }
}
