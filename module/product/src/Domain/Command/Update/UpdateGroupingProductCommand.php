<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Command\Update;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

/**
 */
class UpdateGroupingProductCommand implements DomainCommandInterface
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $templateId;

    /**
     * @var CategoryId[]
     *
     * @JMS\Type("array<string, Ergonode\SharedKernel\Domain\Aggregate\CategoryId>")
     */
    private array $categories;

    /**
     * @var ValueInterface[]
     *
     * @JMS\Type("array<string, Ergonode\Value\Domain\ValueObject\ValueInterface>")
     */
    private array $attributes;

    /**
     * @param ProductId  $productId
     * @param TemplateId $templateId
     * @param array      $categories
     * @param array      $attributes
     */
    public function __construct(
        ProductId $productId,
        TemplateId $templateId,
        array $categories = [],
        array $attributes = []
    ) {
        Assert::allIsInstanceOf($categories, CategoryId::class);
        Assert::allIsInstanceOf($attributes, ValueInterface::class);

        $this->id = $productId;
        $this->templateId = $templateId;
        $this->categories = $categories;
        $this->attributes = $attributes;
    }

    /**
     * @return ProductId
     */
    public function getId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return TemplateId
     */
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
