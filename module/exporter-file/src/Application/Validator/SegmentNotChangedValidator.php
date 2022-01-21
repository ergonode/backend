<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Application\Validator;

use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SegmentNotChangedValidator extends ConstraintValidator
{

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof SegmentNotChanged) {
            throw new UnexpectedTypeException($constraint, SegmentNotChanged::class);
        }

        if (!$value instanceof ExporterFileConfigurationModel) {
            throw new UnexpectedTypeException($value, ExporterFileConfigurationModel::class);
        }

        if (null === $value->channel) {
            return;
        }

        if (($value->channel->getSegmentId() ? $value->channel->getSegmentId()->getValue() : null) !==
            ($value->segmentId ?: null)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('segmentId')
                ->addViolation();
        }
    }
}
