<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Service;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
interface ConfigurationStrategyInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports(string $type): bool;

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getConfiguration(Language $language): array;
}
