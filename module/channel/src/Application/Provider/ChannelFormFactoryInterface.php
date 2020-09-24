<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Provider;

use Symfony\Component\Form\FormInterface;
use Ergonode\Channel\Domain\Entity\AbstractChannel;

/**
 */
interface ChannelFormFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param AbstractChannel|null $channel
     *
     * @return FormInterface
     */
    public function create(AbstractChannel $channel = null): FormInterface;
}
