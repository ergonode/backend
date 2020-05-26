<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Provider;

use Ergonode\Importer\Infrastructure\Action\ImportActionInterface;

/**
 */
class ImportActionProvider
{
    /**
     * @var ImportActionInterface[]
     */
    private array $actions;

    /**
     * @param ImportActionInterface ...$actions
     */
    public function __construct(ImportActionInterface ...$actions)
    {
        $this->actions = $actions;
    }

    /**
     * @param string $type
     *
     * @return ImportActionInterface
     */
    public function provide(string $type): ImportActionInterface
    {
        foreach ($this->actions as $action) {
            if (strtoupper($type) === $action->getType()) {
                return $action;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find import action %s', $type));
    }
}
