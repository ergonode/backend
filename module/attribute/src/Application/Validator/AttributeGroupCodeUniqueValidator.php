<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Validator;

use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AttributeGroupCodeUniqueValidator extends ConstraintValidator
{
    private AttributeGroupQueryInterface $query;

    public function __construct(AttributeGroupQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                                    $value
     * @param AttributeGroupCodeUnique|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AttributeGroupCodeUnique) {
            throw new UnexpectedTypeException($constraint, AttributeGroupCodeUnique::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;
        if (!AttributeGroupCode::isValid($value)) {
            return;
        }

        if ($this->query->checkAttributeGroupExistsByCode(
            new AttributeGroupCode($value)
        )) {
            $this->context->buildViolation($constraint->uniqueMessage)
                ->addViolation();
        }
    }
}
