<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\TranslationDeepl\Infrastructure\Provider;

use Scn\DeeplApiConnector\Model\Usage;

interface UsageDeeplProviderInterface
{
    public function provide(): Usage;
}
