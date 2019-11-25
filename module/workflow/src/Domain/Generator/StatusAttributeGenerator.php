<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Generator;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusAttribute;

/**
 */
class StatusAttributeGenerator
{
    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @throws \Exception
     */
    public function generate(): void
    {
        $attributeCode = new AttributeCode(StatusAttribute::CODE);
        $attributeId = AttributeId::fromKey($attributeCode);
        $attribute = $this->attributeRepository->load($attributeId);
        if (null === $attribute) {
            $attribute = new StatusAttribute(
                $attributeId,
                $attributeCode,
                new TranslatableString(),
                new TranslatableString(),
                new TranslatableString()
            );
            $this->attributeRepository->save($attribute);
        }
    }
}
