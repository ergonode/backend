<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Create;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class CreateSimpleProductCommand implements CreateProductCommandInterface
{
    private ProductId $id;

    private Sku $sku;

    private TemplateId $templateId;

    /**
     * @var CategoryId[]
     */
    private array $categories;

    /**
     * @var ValueInterface[]
     */
    private array $attributes;

    /**
     * @param array $categories
     * @param array $attributes
     */
    public function __construct(
        ProductId $id,
        Sku $sku,
        TemplateId $templateId,
        array $categories = [],
        array $attributes = []
    ) {
        Assert::allIsInstanceOf($categories, CategoryId::class);
        Assert::allIsInstanceOf($attributes, ValueInterface::class);

        $this->id = $id;
        $this->sku = $sku;
        $this->templateId = $templateId;
        $this->categories = $categories;
        $this->attributes = $attributes;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getSku(): Sku
    {
        return $this->sku;
    }

    public function getTemplateId(): TemplateId
    {
        return $this->templateId;
    }

    /**
     * @return array
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
