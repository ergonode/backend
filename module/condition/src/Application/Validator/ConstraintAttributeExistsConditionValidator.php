<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Application\Validator;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 */
class ConstraintAttributeExistsConditionValidator extends ConstraintValidator
{
    /**
     * @var AttributeQueryInterface
     */
    private $attributeQuery;

    /**
     * @param AttributeQueryInterface $attributeQuery
     */
    public function __construct(AttributeQueryInterface $attributeQuery)
    {
        $this->attributeQuery = $attributeQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ConstraintAttributeExistsCondition) {
            throw new UnexpectedTypeException($constraint, ConstraintAttributeExistsCondition::class);
        }

        if (!is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }

        if (!array_key_exists('code', $value)) {
            $this->context
                ->buildViolation('Attribute code not set')
                ->addViolation();

            return;
        }

        $code = new AttributeCode($value['code']);
        if (null === $this->attributeQuery->findAttributeByCode($code)) {
            $this->context
                ->buildViolation('Attribute code "value" not found')
                ->setParameter('value', $value['code'])
                ->atPath('code')
                ->addViolation();
        }
    }
}
