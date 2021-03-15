<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Provider;

use Ergonode\Importer\Domain\Command\CreateSourceCommandInterface;
use Symfony\Component\Form\FormInterface;

interface CreateSourceCommandBuilderInterface
{
    public function supported(string $type): bool;

    public function build(FormInterface $form): CreateSourceCommandInterface;
}
