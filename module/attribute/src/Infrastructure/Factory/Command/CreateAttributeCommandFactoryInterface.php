<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Factory\Command;

use Ergonode\Attribute\Domain\Command\AttributeCommandInterface;
use Symfony\Component\Form\FormInterface;

interface CreateAttributeCommandFactoryInterface
{
    public function support(string $type): bool;

    /**
     * @throws \Exception
     */
    public function create(FormInterface $form): AttributeCommandInterface;
}
