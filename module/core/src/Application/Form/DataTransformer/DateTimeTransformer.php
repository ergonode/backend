<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateTimeTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof \DateTimeInterface) {
            throw new TransformationFailedException('value must be Date');
        }

        return $value->format(\DateTimeInterface::RFC3339);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value): ?\DateTime
    {
        if (empty($value)) {
            return null;
        }

        $denormalized = \DateTime::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $value);

        if ($denormalized) {
            return $denormalized;
        }

        $denormalized = \DateTime::createFromFormat(\DateTimeInterface::RFC3339, $value);

        if ($denormalized) {
            return $denormalized;
        }

        throw new TransformationFailedException('Expected date in ISO8601 format.');
    }
}
