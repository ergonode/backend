<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Event;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CategoryCreatedEvent implements DomainEventInterface
{
    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("Ergonode\Category\Domain\ValueObject\CategoryCode")
     */
    private $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $name;

    /**
     * @var ValueInterface[]
     *
     * @JMS\Type("array<string, Ergonode\Value\Domain\ValueObject\ValueInterface>")
     */
    private $attributes;

    /**
     * @param CategoryId         $id
     * @param CategoryCode       $code
     * @param TranslatableString $name
     * @param ValueInterface[]   $attributes
     */
    public function __construct(CategoryId $id, CategoryCode $code, TranslatableString $name, array $attributes = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
        $this->attributes = $attributes;
    }

    /**
     * @return AbstractId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return CategoryCode
     */
    public function getCode(): CategoryCode
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
     * @return ValueInterface[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
