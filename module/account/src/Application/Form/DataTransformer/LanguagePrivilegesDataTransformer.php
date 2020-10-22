<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form\DataTransformer;

use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class LanguagePrivilegesDataTransformer implements DataTransformerInterface
{
    /**
     * @param LanguagePrivileges|null $value
     *
     * @return array|null
     */
    public function transform($value): ?array
    {
        if ($value) {
            if ($value instanceof LanguagePrivileges) {
                return [
                    'read' => $value->isReadable(),
                    'edit' => $value->isEditable(),
                ];
            }
            throw new TransformationFailedException('Invalid LanguagePrivileges object');
        }

        return null;
    }

    /**
     * @param array|null $value
     *
     * @return LanguagePrivileges|null
     */
    public function reverseTransform($value): ?LanguagePrivileges
    {
        if (isset($value['read'], $value['edit'])) {
            try {
                return new LanguagePrivileges($value['read'], $value['edit']);
            } catch (\InvalidArgumentException $e) {
                throw new TransformationFailedException(
                    sprintf(
                        'invalid language privileges %s value',
                        implode(',', $value)
                    )
                );
            }
        }

        return null;
    }
}
