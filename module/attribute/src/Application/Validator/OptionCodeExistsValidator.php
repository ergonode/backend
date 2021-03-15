<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Validator;

use Ergonode\Attribute\Application\Form\Model\Option\SimpleOptionModel;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class OptionCodeExistsValidator extends ConstraintValidator
{
    private OptionQueryInterface $optionQuery;

    public function __construct(OptionQueryInterface $optionQuery)
    {
        $this->optionQuery = $optionQuery;
    }

    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof OptionCodeExists) {
            throw new UnexpectedTypeException($constraint, OptionCodeExists::class);
        }

        if (!$value instanceof SimpleOptionModel) {
            throw new UnexpectedTypeException($value, SimpleOptionModel::class);
        }

        if (null === $value->code) {
            return;
        }

        if (null === $value->optionId) {
            if ($this->optionQuery->findIdByAttributeIdAndCode($value->attributeId, new OptionKey($value->code))) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $value->code)
                    ->atPath('code')
                    ->addViolation();
            }

            return;
        }
        $optionId = $this->optionQuery->findIdByAttributeIdAndCode($value->attributeId, new OptionKey($value->code));

        if (null !== $optionId && !$optionId->isEqual($value->optionId)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->code)
                ->atPath('code')
                ->addViolation();
        }
    }
}
