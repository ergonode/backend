<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Application;

use Symfony\Component\HttpKernel\Bundle\Bundle;

abstract class AbstractModule extends Bundle
{
    /**
     * {@inheritDoc}
     */
    protected function getContainerExtensionClass(): string
    {
        $basename = preg_replace('/Bundle$/', '', $this->getName());

        return \sprintf('%s\\Application\\DependencyInjection\\%sExtension', $this->getNamespace(), $basename);
    }
}
