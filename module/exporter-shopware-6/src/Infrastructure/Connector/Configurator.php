<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector;

class Configurator
{
    public function configure(HeaderProviderInterface $action, ?string $token): void
    {
        $action->addHeader('Cache-Control', 'no-cache');
        $action->addHeader('Content-Type', 'application/json');
        $action->addHeader('Accept', '*/*');

        if ($token) {
            $authorizationHeader = sprintf('Bearer %s', $token);
            $action->addHeader('Authorization', $authorizationHeader);
        }
    }
}
