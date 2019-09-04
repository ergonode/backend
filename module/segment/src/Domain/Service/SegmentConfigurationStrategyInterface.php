<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Service;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
interface SegmentConfigurationStrategyInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function isSupportedBy(string $type): bool;

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getConfiguration(Language $language): array;
}
