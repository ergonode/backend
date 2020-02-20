<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Application\Model\Type;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class ImportStepModel
{
    /**
     * @var bool
     */
    public bool $attributes;

    /**
     * @var bool
     */
    public bool $templates;

    /**
     * @var bool
     */
    public bool $categories;

    /**
     * @var bool
     */
    public bool $multimedia;

    /**
     * @var bool
     */
    public bool $products;

    /**
     * @param bool $attributes
     * @param bool $templates
     * @param bool $categories
     * @param bool $multimedia
     * @param bool $products
     */
    public function __construct(
        bool $attributes = false,
        bool $templates = false,
        bool $categories = false,
        bool $multimedia = false,
        bool $products = false
    ) {
        $this->attributes = $attributes;
        $this->templates = $templates;
        $this->categories = $categories;
        $this->multimedia = $multimedia;
        $this->products = $products;
    }
}
