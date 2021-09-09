<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Validator;

use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NotTheSameProductValidator extends ConstraintValidator
{
    private EventStoreManagerInterface $manager;

    public function __construct(EventStoreManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param mixed                    $value
     * @param ProductExists|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof NotTheSameProduct) {
            throw new UnexpectedTypeException($constraint, NotTheSameProduct::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;

        if (!ProductId::isValid($value)) {
            return;
        }
            $aggregate = $this->manager->load($constraint->aggregateId);

        if (!$aggregate instanceof AbstractProduct) {
            $this->context->buildViolation($constraint->messageNotProduct)
                ->setParameter('{{ value }}', (string) $constraint->aggregateId)
                ->addViolation();
        }

        if ($value === $constraint->aggregateId->getValue()) {
            $this->context->buildViolation($constraint->messageSameProduct)
                ->addViolation();
        }
    }
}
