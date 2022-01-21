<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Validator;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Application\Model\Product\Attribute\Update\UpdateAttributeValueFormModel;
use Ergonode\Product\Application\Model\Product\Attribute\Update\UpdateProductAttributeFormModel;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
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

        if (!$value instanceof UpdateProductAttributeFormModel) {
            return; //maybe Exception
        }

        if ($value->id === null || !ProductId::isValid($value->id)) {
            return;
        }

        $i = 0;
        foreach ($value->payload as $payloadValues) {
            if ($payloadValues->id === null || !AttributeId::isValid($payloadValues->id)) {
                continue;
            }
            $attribute = $this->attributeRepository->load(new AttributeId($payloadValues->id));
            if (null === $attribute) {
                continue;
            }
            $j = 0;
            foreach ($payloadValues->values as $valueRow) {
                if (null === $valueRow->language || !Language::isValid($valueRow->language)) {
                    continue;
                }
                $secondConstraint = $this->provider->provide(
                    $attribute,
                    new ProductId($value->id),
                    new Language($valueRow->language)
                );
                $violations = $this->validator->validate(['value' => $valueRow->value], $secondConstraint);
                if (0 < $violations->count()) {
                    /** @var ConstraintViolation $violation */
                    foreach ($violations as $violation) {
                        $this->context
                            ->buildViolation($violation->getMessage())
                            ->atPath('payload['.$i.'].values['.$j.'].value')
                            ->addViolation();
                    }
                }
                $j++;
                $secondConstraint = null;
            }
            $attribute = null;
            $i++;
        }
    }
}
