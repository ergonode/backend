<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Form\Product;

use Symfony\Component\Form\FormTypeInterface;

interface ProductFormInterface extends FormTypeInterface
{
    public function supported(string $type): bool;
}
