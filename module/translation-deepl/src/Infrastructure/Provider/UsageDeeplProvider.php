<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\TranslationDeepl\Infrastructure\Provider;

use Scn\DeeplApiConnector\DeeplClient;
use Scn\DeeplApiConnector\Model\Usage;

class UsageDeeplProvider implements UsageDeeplProviderInterface
{
    private string $deeplAuthKey;

    public function __construct(string $deeplAuthKey)
    {
        $this->deeplAuthKey = $deeplAuthKey;
    }

    /**
     * {@inheritDoc}
     */
    public function provide(): Usage
    {
        $deepl = DeeplClient::create($this->deeplAuthKey);

        return $deepl->getUsage();
    }
}
