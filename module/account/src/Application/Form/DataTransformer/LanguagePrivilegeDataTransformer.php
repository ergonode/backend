<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form\DataTransformer;

use Ergonode\Account\Domain\ValueObject\LanguagePrivilege;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 */
class LanguagePrivilegeDataTransformer implements DataTransformerInterface
{
    /**
     * @param LanguagePrivilege|null $value
     *
     * @return null|string
     */
    public function transform($value): ?string
    {
        if ($value) {
            if ($value instanceof LanguagePrivilege) {
                return $value->getValue();
            }
            throw new TransformationFailedException('Invalid Language Privilege object');
        }

        return null;
    }

    /**
     * @param string|null $value
     *
     * @return LanguagePrivilege|null
     */
    public function reverseTransform($value): ?LanguagePrivilege
    {
        if ($value) {
            try {
                return new LanguagePrivilege($value);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(sprintf('Invalid Language Privilege "%s" value', $value));
            }
        }

        return null;
    }
}
