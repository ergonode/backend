<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Provider;

use Ergonode\Attribute\Domain\AttributeValidatorInterface;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;

/**
 */
class AttributeValidationProvider
{
    /**
     * @var AttributeValidatorInterface[]
     */
    private $validators;

    /**
     * @param AttributeValidatorInterface ...$validators
     */
    public function __construct(AttributeValidatorInterface ...$validators)
    {
        $this->validators = $validators;
    }

    /**
     * @param AbstractAttribute $attribute
     *
     * @return AttributeValidatorInterface
     */
    public function provide(AbstractAttribute $attribute): AttributeValidatorInterface
    {
        foreach ($this->validators as $validator) {
            if ($validator->isSupported($attribute)) {
                return $validator;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find validation for %s', $attribute->getType()));
    }
}
