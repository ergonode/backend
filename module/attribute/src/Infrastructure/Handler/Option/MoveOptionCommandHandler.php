<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Option;

use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Command\Option\MoveOptionCommand;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Entity\AbstractOption;

class MoveOptionCommandHandler
{
    private OptionRepositoryInterface $optionRepository;

    private AttributeRepositoryInterface $attributeRepository;

    public function __construct(
        OptionRepositoryInterface $optionRepository,
        AttributeRepositoryInterface $attributeRepository
    ) {
        $this->optionRepository = $optionRepository;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(MoveOptionCommand $command): void
    {
        /** @var AbstractOptionAttribute $attribute */
        $attribute = $this->attributeRepository->load($command->getAttributeId());
        Assert::isInstanceOf($attribute, AbstractOptionAttribute::class);

        $option = $this->optionRepository->load($command->getId());
        Assert::isInstanceOf($option, AbstractOption::class);

        $position = null;
        if ($command->getPositionId()) {
            $position = $this->optionRepository->load($command->getPositionId());
            Assert::isInstanceOf($position, AbstractOption::class);
        }

        $attribute->moveOption($option, $command->isAfter(), $position);

        $this->attributeRepository->save($attribute);
    }
}
