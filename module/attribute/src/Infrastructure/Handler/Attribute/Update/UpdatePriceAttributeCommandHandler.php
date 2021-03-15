<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Attribute\Update;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Infrastructure\Handler\Attribute\AbstractUpdateAttributeCommandHandler;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Command\Attribute\Update\UpdatePriceAttributeCommand;

class UpdatePriceAttributeCommandHandler extends AbstractUpdateAttributeCommandHandler
{
    private AttributeRepositoryInterface $attributeRepository;

    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdatePriceAttributeCommand $command): void
    {
        /** @var PriceAttribute $attribute */
        $attribute = $this->attributeRepository->load($command->getId());

        if (!$attribute instanceof PriceAttribute) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    PriceAttribute::class,
                    get_debug_type($attribute)
                )
            );
        }
        $this->update($command, $attribute);
        $attribute->changeCurrency($command->getCurrency());

        $this->attributeRepository->save($attribute);
    }
}
