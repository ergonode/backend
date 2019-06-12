<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 */
abstract class AbstractModule extends Bundle
{
    /**
     * Returns the bundle's container extension class.
     *
     * @return string
     */
    protected function getContainerExtensionClass(): string
    {
        $basename = preg_replace('/Bundle$/', '', $this->getName());

        return \sprintf('%s\\Application\\DependencyInjection\\%sExtension', $this->getNamespace(), $basename);
    }
}
