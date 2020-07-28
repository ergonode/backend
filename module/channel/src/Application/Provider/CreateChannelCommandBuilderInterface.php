<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Provider;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Symfony\Component\Form\FormInterface;

/**
 */
interface CreateChannelCommandBuilderInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param FormInterface $form
     *
     * @return DomainCommandInterface
     */
    public function build(FormInterface $form): DomainCommandInterface;
}
