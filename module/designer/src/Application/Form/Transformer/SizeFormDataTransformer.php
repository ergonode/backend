<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\Form\Transformer;

use Ergonode\Designer\Domain\ValueObject\Size;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class SizeFormDataTransformer
 */
class SizeFormDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($value): ?array
    {
        if ($value) {
            if ($value instanceof Size) {
                return [
                    'width' => $value->getWidth(),
                    'height' => $value->getHeight(),
                ];
            }
            throw new TransformationFailedException('Invalid Size object');
        }

        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @param array|null $value
     */
    public function reverseTransform($value): ?Size
    {
        if (isset($value['width'], $value['height'])) {
            try {
                return new Size($value['width'], $value['height']);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('invalid size %s value', implode(',', $value)));
            }
        }

        return null;
    }
}
