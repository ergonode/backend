<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ProductCollectionElementUpdateFormModel
{
    /**
     * @Assert\NotNull(),
     * @Assert\Type("boolean")
     */
    public ?bool $visible;

    public function __construct()
    {
        $this->visible = null;
    }
}
