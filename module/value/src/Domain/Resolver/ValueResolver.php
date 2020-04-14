<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Domain\Resolver;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class ValueResolver
{
    /**
     * @param Language            $language
     * @param ValueInterface|null $value
     *
     * @return string|null
     */
    public function resolve(Language $language, ?ValueInterface $value = null): ?string
    {
        if (null === $value) {
            return null;
        }

        if ($value instanceof StringValue) {
            return (string) $value;
        }

        if ($value instanceof TranslatableStringValue) {
            return $value->getVersion($language);
        }

        if ($value instanceof StringCollectionValue) {
            return implode(', ', $value->getValue());
        }

        throw new \RuntimeException('Cant resolve ValueInterface');
    }
}
