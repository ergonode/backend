<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Factory\Command;

use Ergonode\Attribute\Domain\Command\AttributeCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

interface UpdateAttributeCommandFactoryInterface
{
    public function support(string $type): bool;

    public function create(AttributeId $id, FormInterface $form): AttributeCommandInterface;
}
