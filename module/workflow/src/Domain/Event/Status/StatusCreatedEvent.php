<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Status;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class StatusCreatedEvent implements DomainEventInterface
{
    /**
     * @var StatusId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $id;

    /**
     * @var StatusCode
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private StatusCode $code;

    /**
     * @var Color
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Color")
     */
    private Color $color;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $description;

    /**
     * @param StatusId           $id
     * @param StatusCode         $code
     * @param Color              $color
     * @param TranslatableString $name
     * @param TranslatableString $description
     */
    public function __construct(
        StatusId $id,
        StatusCode $code,
        Color $color,
        TranslatableString $name,
        TranslatableString $description
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->color = $color;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return StatusId
     */
    public function getAggregateId(): StatusId
    {
        return $this->id;
    }

    /**
     * @return StatusCode
     */
    public function getCode(): StatusCode
    {
        return $this->code;
    }

    /**
     * @return Color
     */
    public function getColor(): Color
    {
        return $this->color;
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
}
