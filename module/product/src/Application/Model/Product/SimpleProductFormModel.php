<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Model\Product;

use Ergonode\Designer\Infrastructure\Validator\TemplateExists;
use Ergonode\Product\Infrastructure\Validator\Sku;
use Ergonode\Product\Infrastructure\Validator\SkuUnique;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

/**
 */
class SimpleProductFormModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Sku is required", groups={"Create"})
     *
     * @Sku(groups={"Create"})
     *
     * @SkuUnique(groups={"Create"})
     */
    public ?string $sku = null;

    /**
     * @var CategoryId[]
     */
    public array $categories = [];

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Template is required")
     * @Assert\Uuid()
     *
     * @TemplateExists()
     */
    public ?string $template = null;
}
