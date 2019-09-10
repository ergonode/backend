<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Service;

use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
interface ConditionVerifierStrategyInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function isSupportedBy(string $type): bool;

    /**
     * @param AbstractProduct    $object
     * @param ConditionInterface $configuration
     *
     * @return bool
     */
    public function verify(AbstractProduct $object, ConditionInterface $configuration): bool;
}
