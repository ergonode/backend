<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Model;

/**
 * Class AttributeParametersModel
 */
class AttributeParametersModel
{
    /**
     * @var array
     */
    public $formats;

    /**
     * @var string
     */
    public $format;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $unit;

    /**
     */
    public function __construct()
    {
        $this->formats = [];
    }
}
