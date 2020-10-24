<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class ProductCreatedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @JMS\Type("Ergonode\Product\Domain\ValueObject\Sku")
     */
    private Sku $sku;

    /**
     * @JMS\Type("string")
     */
    private string $type;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $templateId;

    /**
     * @var CategoryId[]
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
     * @param array $categories
     * @param array $attributes
     */
    public function __construct(
        ProductId $id,
        Sku $sku,
        string $type,
        TemplateId $templateId,
        array $categories = [],
        array $attributes = []
    ) {
        $this->id = $id;
        $this->sku = $sku;
        $this->type = $type;
        $this->templateId = $templateId;
        $this->categories = $categories;
        $this->attributes = $attributes;
    }

    public function getAggregateId(): ProductId
    {
        return $this->id;
    }

    public function getSku(): Sku
    {
        return $this->sku;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTemplateId(): TemplateId
    {
        return $this->templateId;
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
