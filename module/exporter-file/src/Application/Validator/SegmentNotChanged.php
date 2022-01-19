<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SegmentNotChanged extends Constraint
{
    public string $message = 'Can\'t change segment while channel editing';

    /**
     * {@inheritdoc}
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
