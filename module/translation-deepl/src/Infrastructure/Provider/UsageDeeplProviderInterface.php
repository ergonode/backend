<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Provider;

use Scn\DeeplApiConnector\Model\Usage;

/**
 */
interface UsageDeeplProviderInterface
{
    /**
     * @return Usage
     */
    public function provide(): Usage;
}
