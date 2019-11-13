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
use Ergonode\Value\Domain\ValueObject\TranslatableCollectionValue;
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
            return $value->getValue();
        }

        if ($value instanceof TranslatableStringValue) {
            return $value->getValue()->get($language);
        }

        if ($value instanceof StringCollectionValue) {
            return implode(', ', $value->getValue());
        }

        if ($value instanceof TranslatableCollectionValue) {
            $result = [];
            foreach ($value->getValue() as $translatableString) {
                $result[] = $translatableString->get($language);
            }

            return implode(', ', $result);
        }

        throw new \RuntimeException('Cant resolve ValueInterface');
    }
}
