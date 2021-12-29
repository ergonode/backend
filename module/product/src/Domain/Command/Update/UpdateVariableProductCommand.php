<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Update;

use Ergonode\Product\Domain\Command\ProductCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class UpdateVariableProductCommand implements ProductCommandInterface
{
    private ProductId $id;

    private TemplateId $templateId;

    /**
     * @var CategoryId[]
     */
    private array $categories;

    /**
     * @param array $categories
     */
    public function __construct(
        ProductId $productId,
        TemplateId $templateId,
        array $categories = []
    ) {
        Assert::allIsInstanceOf($categories, CategoryId::class);

        $this->id = $productId;
        $this->templateId = $templateId;
        $this->categories = $categories;
    }

    public function getId(): ProductId
    {
        return $this->id;
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
}
