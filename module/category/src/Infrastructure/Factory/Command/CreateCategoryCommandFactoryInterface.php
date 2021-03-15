<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Factory\Command;

use Ergonode\Category\Domain\Command\CreateCategoryCommandInterface;
use Symfony\Component\Form\FormInterface;

interface CreateCategoryCommandFactoryInterface
{
    public function support(string $type): bool;

    /**
     * @throws \Exception
     */
    public function create(FormInterface $form): CreateCategoryCommandInterface;
}
