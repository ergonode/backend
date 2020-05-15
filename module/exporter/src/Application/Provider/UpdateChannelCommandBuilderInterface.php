<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Provider;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Symfony\Component\Form\FormInterface;

/**
 */
interface UpdateChannelCommandBuilderInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param ChannelId     $channelId
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     */
    public function build(ChannelId $channelId, FormInterface $form): DomainCommandInterface;
}
