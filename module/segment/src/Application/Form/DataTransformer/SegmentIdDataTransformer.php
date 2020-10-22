<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Application\Form\DataTransformer;

use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class SegmentIdDataTransformer implements DataTransformerInterface
{
    /**
     * @param SegmentId|null $value
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof SegmentId) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid Segment Id object');
        }

        return null;
    }

    /**
     * @param string|null $value
     */
    public function reverseTransform($value): ?SegmentId
    {
        if ($value) {
            try {
                return new SegmentId($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid Segment Id "%s" value', $value));
            }
        }

        return null;
    }
}
