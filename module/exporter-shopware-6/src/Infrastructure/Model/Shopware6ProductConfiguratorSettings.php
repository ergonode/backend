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
class Shopware6ProductConfiguratorSettings
{
    /**
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("id")
     */
    private ?string $id;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     * @JMS\SerializedName("optionId")
     */
    private ?string $optionId;

    /**
     * @param string|null $id
     * @param string|null $optionId
     */
    public function __construct(?string $id = null, ?string $optionId = null)
    {
        $this->id = $id;
        $this->optionId = $optionId;
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
    public function getOptionId(): ?string
    {
        return $this->optionId;
    }

    /**
     * @param string|null $optionId
     */
    public function setOptionId(?string $optionId): void
    {
        $this->optionId = $optionId;
    }
}
