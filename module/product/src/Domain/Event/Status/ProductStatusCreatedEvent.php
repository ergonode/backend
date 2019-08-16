<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event\Status;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Product\Domain\Entity\ProductStatusId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductStatusCreatedEvent implements DomainEventInterface
{
    /**
     * @var ProductStatusId
     *
     * @JMS\Type("Ergonode\Product\Domain\Entity\ProductStatusId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("status")
     */
    private $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $name;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $description;

    /**
     * @var Color
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Color")
     */
    private $color;

    /**
     * @param ProductStatusId    $id
     * @param string             $code
     * @param Color             $color
     * @param TranslatableString $name
     * @param TranslatableString $description
     */
    public function __construct(ProductStatusId $id, string $code, Color $color, TranslatableString $name, TranslatableString $description)
    {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
        $this->color = $color;
    }

    /**
     * @return ProductStatusId
     */
    public function getId(): ProductStatusId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }

    /**
     * @return TranslatableString
     */
    public function getDescription(): TranslatableString
    {
        return $this->description;
    }

    /**
     * @return Color
     */
    public function getColor(): Color
    {
        return $this->color;
    }
}
