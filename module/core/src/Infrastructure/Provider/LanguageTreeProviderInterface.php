<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Provider;

use Ergonode\Core\Domain\ValueObject\Language;

interface LanguageTreeProviderInterface
{
    /**
     * @return array
     */
    public function getActiveLanguages(Language $language): array;
}
