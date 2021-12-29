<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Validator;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AttributeCodeUniqueValidator extends ConstraintValidator
{
    private AttributeQueryInterface $query;

    public function __construct(AttributeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                                    $value
     * @param AttributeCodeUnique|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AttributeCodeUnique) {
            throw new UnexpectedTypeException($constraint, AttributeCodeUnique::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;
        if (!AttributeCode::isValid($value)) {
            return;
        }

        if ($this->query->checkAttributeExistsByCode(
            new AttributeCode($value)
        )) {
            $this->context->buildViolation($constraint->uniqueMessage)
                ->addViolation();
        }
    }
}
