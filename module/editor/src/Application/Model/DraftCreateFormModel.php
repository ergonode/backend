<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class DraftCreateFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $productId;
}
