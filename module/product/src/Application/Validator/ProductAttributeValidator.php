<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Validator;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintProvider;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ProductAttributeValidator extends ConstraintValidator
{
    private AttributeRepositoryInterface $attributeRepository;

    private AttributeValueConstraintProvider $provider;

    private ValidatorInterface $validator;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeValueConstraintProvider $provider,
        ValidatorInterface $validator
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->provider = $provider;
        $this->validator = $validator;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductAttribute) {
            throw new UnexpectedTypeException($constraint, ProductAttribute::class);
        }

        if (null === $value || '' === $value) {
            return;
        }
//        if($value instanceof Att)
dump($value);die;
        $attributeId = new AttributeId($value->id);
        $attribute = $this->attributeRepository->load($attributeId);

        $secondConstraint = $this->provider->provide($attribute);
        $i = 0;
        foreach ($value->values as $valueRow) {
            $violations = $this->validator->validate(['value' => $valueRow->value], $secondConstraint);
            if (0 < $violations->count()) {
                /** @var ConstraintViolation $violation */
                foreach ($violations as $violation) {
                    $this->context
                        ->buildViolation($violation->getMessage())
                        ->atPath('values[' . $i . '].value')
                        ->addViolation();
                }
            }
            $i++;
        }
    }
}
