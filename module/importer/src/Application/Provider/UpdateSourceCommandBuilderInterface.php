<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Provider;

use Ergonode\Importer\Domain\Command\UpdateSourceCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

interface UpdateSourceCommandBuilderInterface
{
    public function supported(string $type): bool;

    public function build(SourceId $id, FormInterface $form): UpdateSourceCommandInterface;
}
