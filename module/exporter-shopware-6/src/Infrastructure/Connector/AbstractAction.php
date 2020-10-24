<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector;

abstract class AbstractAction implements HeaderProviderInterface
{
    /**
     * @var array
     */
    private array $header = [];

    /**
     * @return array
     */
    public function buildHeaders(): array
    {
        return $this->header;
    }

    public function addHeader(string $key, string $value): void
    {
        $this->header[$key] = $value;
    }
}
