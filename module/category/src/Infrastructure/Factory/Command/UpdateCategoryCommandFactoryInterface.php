<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Factory\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Symfony\Component\Form\FormInterface;

interface UpdateCategoryCommandFactoryInterface
{
    public function support(string $type): bool;

    public function create(CategoryId $id, FormInterface $form): DomainCommandInterface;
}
