<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector;

interface HeaderProviderInterface
{
    public function addHeader(string $key, string $value): void;

    /**
     * @return array
     */
    public function buildHeaders(): array;
}
