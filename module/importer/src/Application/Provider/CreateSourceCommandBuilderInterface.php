<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Provider;

use Symfony\Component\Form\FormInterface;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

interface CreateSourceCommandBuilderInterface
{
    public function supported(string $type): bool;

    public function build(FormInterface $form): DomainCommandInterface;
}
