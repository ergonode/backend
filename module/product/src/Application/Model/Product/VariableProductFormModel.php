<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Model\Product;

use Ergonode\Designer\Infrastructure\Validator\TemplateExists;
use Ergonode\Product\Infrastructure\Validator\Sku;
use Ergonode\Product\Infrastructure\Validator\SkuExists;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Attribute\Infrastructure\Validator\AttributeExists;
use Ergonode\Attribute\Infrastructure\Validator\AttributeTypeValid;

/**
 */
class VariableProductFormModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Sku is required", groups={"Create"})
     *
     * @Sku(groups={"Create"})
     *
     * @SkuExists(groups={"Create"})
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

    /**
     * @var array
     *
     * @Assert\Count(min=1)
     * @Assert\All({
     *    @Assert\NotBlank(message="Bind attribute is required"),
     *    @Assert\Uuid(strict=true),
     *
     *    @AttributeExists(),
     *
     *    @AttributeTypeValid(type="SELECT")
     * })
     */
    public array $bindings = [];
}
