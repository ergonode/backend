<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Provider;

use Symfony\Component\Form\FormInterface;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

interface ChannelFormFactoryInterface
{
    public function supported(string $type): bool;

    public function create(AbstractChannel $channel = null): FormInterface;
}
