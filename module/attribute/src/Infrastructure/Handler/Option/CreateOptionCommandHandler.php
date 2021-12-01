<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Option;

use Ergonode\Attribute\Domain\Command\Option\CreateOptionCommand;
use Ergonode\Attribute\Domain\Entity\Option\SimpleOption;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Entity\AbstractOption;

class CreateOptionCommandHandler
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
    public function __invoke(CreateOptionCommand $command): void
    {
        /** @var AbstractOptionAttribute $attribute */
        $attribute = $this->attributeRepository->load($command->getAttributeId());
        Assert::isInstanceOf($attribute, AbstractOptionAttribute::class);

        $option = new SimpleOption(
            $command->getId(),
            $command->getCode(),
            $command->getLabel()
        );

        $position = null;
        if ($command->getPositionId()) {
            $position = $this->optionRepository->load($command->getPositionId());
            Assert::isInstanceOf($position, AbstractOption::class);
        }

        $attribute->addOption($option, $command->isAfter(), $position);

        $this->optionRepository->save($option);
        $this->attributeRepository->save($attribute);
    }
}
