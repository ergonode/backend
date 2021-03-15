<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Factory\Command;

use Ergonode\Product\Domain\Command\Create\CreateProductCommandInterface;
use Symfony\Component\Form\FormInterface;

interface CreateProductCommandFactoryInterface
{
    public function support(string $type): bool;

    /**
     * @throws \Exception
     */
    public function create(FormInterface $form): CreateProductCommandInterface;
}
