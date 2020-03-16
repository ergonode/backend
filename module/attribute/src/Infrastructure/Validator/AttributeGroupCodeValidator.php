<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Validator;

use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class AttributeGroupCodeValidator extends ConstraintValidator
{
    /**
     * @var AttributeGroupQueryInterface
     */
    private AttributeGroupQueryInterface $query;

    /**
     * @param AttributeGroupQueryInterface $query
     */
    public function __construct(AttributeGroupQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                         $value
     * @param AttributeGroupCode|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AttributeGroupCode) {
            throw new UnexpectedTypeException($constraint, AttributeGroupCode::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!\Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode::isValid($value)) {
            $this->context->buildViolation($constraint->validMessage)
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;
        }

        // @todo split into two different validators if possible
        $attribute = $this
            ->query
            ->checkAttributeGroupExistsByCode(
                new \Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode($value)
            );

        if ($attribute) {
            $this->context->buildViolation($constraint->uniqueMessage)
                ->addViolation();
        }
    }
}
