<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Model;

use Ergonode\Designer\Infrastructure\Validator\TemplateExists;
use Ergonode\Product\Infrastructure\Validator\Sku;
use Ergonode\Product\Infrastructure\Validator\SkuExists;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class ProductCreateFormModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Sku is required")
     *
     * @Sku()
     *
     * @SkuExists()
     */
    public ?string $sku;

    /**
     * @var array
     */
    public array $categories;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Template is required")
     * @Assert\Uuid()
     *
     * @TemplateExists()
     */
    public ?string $template;

    /**
     * ProductCreateFormModel constructor.
     */
    public function __construct()
    {
        $this->sku = null;
        $this->categories = [];
        $this->template = null;
    }
}
