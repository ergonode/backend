<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Provider;


use Scn\DeeplApiConnector\Model\TranslationConfig;

interface TranslationDeeplProviderInterface
{
    public function provide(TranslationConfig $translation);
}
