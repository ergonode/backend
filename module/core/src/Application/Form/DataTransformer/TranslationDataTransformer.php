<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Form\DataTransformer;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TranslationDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($value): array
    {
        if ($value) {
            if ($value instanceof TranslatableString) {
                return $value->getTranslations();
            }
            throw new TransformationFailedException('Invalid TranslatableString object');
        }

        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($value): TranslatableString
    {
        if (is_array($value)) {
            try {
                return new TranslatableString($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException('Invalid TranslatableString value');
            }
        }

        return new TranslatableString();
    }
}
