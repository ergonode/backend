<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

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
     * @param Size|null $value
     * @return array|null
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
     * @param array|null $value
     * @return Size|null
     */
    public function reverseTransform($value): ?Size
    {
        if (is_array($value)) {
            try {
                return new Size($value['width'], $value['height']);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('invalid size %s value', implode(',', $value)));
            }
        }

        return null;
    }
}
