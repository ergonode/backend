<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Provider;

/**
 */
class SourceTypeProvider
{
    /**
     * @var array[]
     */
    private array $services;

    /**
     * @param ImportSourceInterface ...$services
     */
    public function __construct(ImportSourceInterface ...$services)
    {
        $this->services = [];
        foreach ($services as $service) {
            $this->services[] = $service->getType();
        }
    }

    /**
     * @return array
     */
    public function provide(): array
    {
        return $this->services;
    }
}
