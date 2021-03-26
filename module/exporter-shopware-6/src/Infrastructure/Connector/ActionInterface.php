<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector;

use GuzzleHttp\Psr7\Request;

interface ActionInterface
{
    public function getRequest(): Request;

    /**
     * @return string|null|object|array
     */
    public function parseContent(?string $content);

    public function isLoggable(): bool;
}
