<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector;

use GuzzleHttp\Psr7\Request;

interface ActionInterface
{
    /**
     * @return Request
     */
    public function getRequest(): Request;

    /**
     * @param string|null $content
     *
     * @return string|null|object|array
     */
    public function parseContent(?string $content);
}
