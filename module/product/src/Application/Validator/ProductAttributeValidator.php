<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Validator;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintProvider;
use Ergonode\Product\Application\Model\Product\Attribute\Update\UpdateAttributeValueFormModel;
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

    /**
     * @param mixed|UpdateAttributeValueFormModel $value
     * @param Constraint|ProductAttribute         $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ProductAttribute) {
            throw new UnexpectedTypeException($constraint, ProductAttribute::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof UpdateAttributeValueFormModel) {
            return; //maybe Exception
        }

        if ($value->id === null || !AttributeId::isValid($value->id)) {
            return;
        }

        $attribute = $this->attributeRepository->load(new AttributeId($value->id));
        if (null === $attribute) {
            return;
        }

        $secondConstraint = $this->provider->provide($attribute);
        $i = 0;
        foreach ($value->values as $valueRow) {
            $violations = $this->validator->validate(['value' => $valueRow->value], $secondConstraint);
            if (0 < $violations->count()) {
                /** @var ConstraintViolation $violation */
                foreach ($violations as $violation) {
                    $this->context
                        ->buildViolation($violation->getMessage())
                        ->atPath('values['.$i.'].value')
                        ->addViolation();
                }
            }
            $i++;
        }
    }
}
