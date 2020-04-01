<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProductCreatedEvent implements DomainEventInterface
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @var Sku
     *
     * @JMS\Type("Ergonode\Product\Domain\ValueObject\Sku")
     */
    private Sku $sku;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $type;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $class;

    /**
     * @var Categoryid[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\CategoryId>")
     */
    private array $categories;

    /**
     * @var ValueInterface[]
     *
     * @JMS\Type("array<string,Ergonode\Value\Domain\ValueObject\ValueInterface>")
     */
    private array $attributes;

    /**
     * ProductCreatedEvent constructor.
     *
     * @param ProductId $id
     * @param Sku       $sku
     * @param string    $type
     * @param string    $class
     * @param array     $categories
     * @param array     $attributes
     */
    public function __construct(
        ProductId $id,
        Sku $sku,
        string $type,
        string $class,
        array $categories = [],
        array $attributes = []
    ) {
        $this->id = $id;
        $this->sku = $sku;
        $this->type = $type;
        $this->class = $class;
        $this->categories = $categories;
        $this->attributes = $attributes;
    }

    /**
     * @return ProductId
     */
    public function getAggregateId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return Sku
     */
    public function getSku(): Sku
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }


    /**
     * @return CategoryId[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return ValueInterface[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
