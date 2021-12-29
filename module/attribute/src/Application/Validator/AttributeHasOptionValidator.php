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
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Application\Form\Model\Option\OptionMoveModel;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;

class AttributeHasOptionValidator extends ConstraintValidator
{
    private AttributeRepositoryInterface $attributeRepository;

    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param mixed                      $value
     * @param AttributeExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {

        if (!$constraint instanceof AttributeHasOption) {
            throw new UnexpectedTypeException($constraint, AttributeHasOption::class);
        }

        if (!$value instanceof OptionMoveModel) {
            throw new UnexpectedTypeException($value, OptionMoveModel::class);
        }

        if (null === $value->positionId) {
            return;
        }

        if ($value->positionId) {
            $attribute = $this->attributeRepository->load($value->attributeId);
            $positionId = new AggregateId($value->positionId);
            if ($attribute instanceof AbstractOptionAttribute && !$attribute->hasOption($positionId)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $value->positionId)
                    ->setParameter('{{ attribute }}', $value->attributeId->getValue())
                    ->atPath('positionId')
                    ->addViolation();
            }
        }
    }
}
