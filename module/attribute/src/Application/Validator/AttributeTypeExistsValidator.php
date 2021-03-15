<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Ergonode\Attribute\Application\Provider\AttributeTypeProvider;

class AttributeTypeExistsValidator extends ConstraintValidator
{
    private AttributeTypeProvider $provider;

    public function __construct(AttributeTypeProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param mixed                    $value
     * @param AttributeExists|Constraint $constraint
     *
     * @throws \ReflectionException
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AttributeTypeExists) {
            throw new UnexpectedTypeException($constraint, AttributeTypeExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $types = $this->provider->provide();

        if (!in_array($value, $types)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
