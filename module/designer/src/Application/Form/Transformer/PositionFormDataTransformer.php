<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @param array|null $value
     */
    public function reverseTransform($value): ?Position
    {
        if (isset($value['x'], $value['y'])) {
            try {
                return new Position($value['x'], $value['y']);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid Position %s value', implode(',', $value)));
            }
        }

        return null;
    }
}
