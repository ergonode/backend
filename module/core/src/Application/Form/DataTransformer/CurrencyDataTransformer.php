<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Form\DataTransformer;

use Ergonode\Core\Domain\ValueObject\Language;
use Money\Currency;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 */
class CurrencyDataTransformer implements DataTransformerInterface
{
    /**
     * @param Language|null $value
     *
     * @return null|string
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof Currency) {
                return $value->getCode();
            }
            throw new TransformationFailedException('Invalid Currency object');
        }

        return null;
    }

    /**
     * @param string|null $value
     *
     * @return Currency|null
     */
    public function reverseTransform($value): ?Currency
    {
        if ($value) {
            try {
                return new Currency($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid Currency "%s" value', $value));
            }
        }

        return null;
    }
}
