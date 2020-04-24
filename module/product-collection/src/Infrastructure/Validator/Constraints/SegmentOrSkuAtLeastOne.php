<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Validator\Constraints;

use Ergonode\ProductCollection\Infrastructure\Validator\SegmentOrSkuAtLeastOneValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"CLASS", "ANNOTATION"})
 *
 */
class SegmentOrSkuAtLeastOne extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'Both fields can\'t be empty';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy(): string
    {
        return SegmentOrSkuAtLeastOneValidator::class;
    }
}
