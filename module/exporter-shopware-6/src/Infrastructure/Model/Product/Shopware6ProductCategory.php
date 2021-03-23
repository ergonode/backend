<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model\Product;

use JMS\Serializer\Annotation as JMS;

class Shopware6ProductCategory
{
    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("id")
     */
    private ?string $id;

    public function __construct(?string $id)
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}
