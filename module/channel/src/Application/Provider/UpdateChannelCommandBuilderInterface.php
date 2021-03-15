<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Provider;

use Ergonode\Channel\Domain\Command\ChannelCommandInterface;
use Symfony\Component\Form\FormInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface UpdateChannelCommandBuilderInterface
{
    public function supported(string $type): bool;

    public function build(ChannelId $id, FormInterface $form): ChannelCommandInterface;
}
