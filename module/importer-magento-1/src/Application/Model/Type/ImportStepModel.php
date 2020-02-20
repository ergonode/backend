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
     */
    public function __construct()
    {
        $this->attributes = false;
        $this->categories = false;
        $this->products = false;
        $this->templates = false;
        $this->multimedia = false;
    }
}
