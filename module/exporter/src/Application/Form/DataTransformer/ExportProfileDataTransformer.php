<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Form\DataTransformer;

use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 */
class ExportProfileDataTransformer implements DataTransformerInterface
{
    /**
     * @param ExportProfileId $value
     *
     * @return string|null
     */
    public function transform($value)
    {
        if ($value) {
            if ($value instanceof ExportProfileId) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid ExportProfileId object');
        }

        return null;
    }

    /**
     * @param string|null $value
     *
     * @return ExportProfileId|null
     */
    public function reverseTransform($value): ?ExportProfileId
    {
        if ($value) {
            try {
                return new ExportProfileId($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid ExportProfileId "%s" value', $value));
            }
        }

        return null;
    }
}
