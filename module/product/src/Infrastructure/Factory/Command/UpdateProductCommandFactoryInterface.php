<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Factory\Command;

use Ergonode\Product\Domain\Command\ProductCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface UpdateProductCommandFactoryInterface
{
    public function support(string $type): bool;

    public function create(ProductId $productId, FormInterface $form): ProductCommandInterface;
}
