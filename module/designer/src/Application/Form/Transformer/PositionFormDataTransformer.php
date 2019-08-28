<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Form\Transformer;

use Ergonode\Designer\Domain\ValueObject\Position;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class PositionFormDataTransformer
 */
class PositionFormDataTransformer implements DataTransformerInterface
{
    /**
     * @param Position|null $value
     * @return array|null
     */
    public function transform($value): ?array
    {
        if ($value) {
            if ($value instanceof Position) {
                return [
                    'x' => $value->getX(),
                    'y' => $value->getY(),
                ];
            }
            throw new TransformationFailedException('Invalid Position object');
        }

        return null;
    }

    /**
     * @param array|null $value
     * @return Position|null
     */
    public function reverseTransform($value): ?Position
    {
        if (is_array($value)) {
            try {
                return new Position((int) $value['x'], (int) $value['y']);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('invalid size %s value', implode(',', $value)));
            }
        }

        return null;
    }
}
