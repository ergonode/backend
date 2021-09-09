<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Domain\ValueObject\TemplateCode;

class TemplateCodeUniqueValidator extends ConstraintValidator
{
    private TemplateQueryInterface $query;

    public function __construct(TemplateQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed                         $value
     * @param TemplateCodeUnique|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof TemplateCodeUnique) {
            throw new UnexpectedTypeException($constraint, TemplateCodeUnique::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!TemplateCode::isValid($value)) {
            return;
        }

        if ($this->query->findTemplateIdByCode(new TemplateCode($value))) {
            $this->context->buildViolation($constraint->uniqueMessage)->addViolation();
        }
    }
}
