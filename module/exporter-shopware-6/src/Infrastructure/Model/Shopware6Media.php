<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use JMS\Serializer\Annotation as JMS;

class Shopware6Media
{
    /**
     * @JMS\Exclude()
     */
    private ?string $id;

    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("fileName")
     */
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
}
