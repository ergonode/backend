<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product;

use Ergonode\Designer\Application\Validator as TemplateAssert;
use Ergonode\Product\Application\Validator as ProductAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class GroupingProductFormModel
{
    /**
     * @Assert\NotBlank(message="Sku is required", groups={"Create"})
     *
     * @ProductAssert\Sku(groups={"Create"})
     * @ProductAssert\SkuUnique(groups={"Create"})
     */
    public ?string $sku = null;

    /**
     * @var CategoryId[]
     */
    public array $categories = [];

    /**
     * @Assert\NotBlank(message="Template is required")
     * @Assert\Uuid()
     *
     * @TemplateAssert\TemplateExists()
     */
    public ?string $template = null;
}
