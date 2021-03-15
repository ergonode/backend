<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Form\Transformer;

use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class OptionKeyDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof OptionKey) {
                return $value->getValue();
            }

            throw new TransformationFailedException('Invalid OptionKey object');
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @param string|null $value
     */
    public function reverseTransform($value): ?OptionKey
    {
        if ($value) {
            try {
                return new OptionKey($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid Option Key %s value', $value));
            }
        }

        return null;
    }
}
