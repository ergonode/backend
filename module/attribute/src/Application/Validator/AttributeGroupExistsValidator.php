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
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

class AttributeGroupExistsValidator extends ConstraintValidator
{
    private AttributeGroupRepositoryInterface $repository;

    public function __construct(AttributeGroupRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param mixed                    $value
     * @param AttributeExists|Constraint $constraint
     *
     * @throws \ReflectionException
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof AttributeGroupExists) {
            throw new UnexpectedTypeException($constraint, AttributeGroupExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        $attributeGroup = false;
        if (AttributeGroupId::isValid($value)) {
            $attributeGroup = $this->repository->load(new AttributeGroupId($value));
        }

        if (!$attributeGroup) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
