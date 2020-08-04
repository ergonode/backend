<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use JMS\Serializer\Annotation as JMS;

/**
 */
class Shopware6Media
{
    /**
     * @var string|null
     *
     * @JMS\Exclude()
     */
    private ?string $id;

    /**
     * @param string|null $id
     */
    public function __construct(?string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
}
