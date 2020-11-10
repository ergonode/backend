<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Provider;

use Ergonode\Channel\Domain\Command\CreateChannelCommandInterface;
use Symfony\Component\Form\FormInterface;

interface CreateChannelCommandBuilderInterface
{
    public function supported(string $type): bool;

    public function build(FormInterface $form): CreateChannelCommandInterface;
}
