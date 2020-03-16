<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler;

use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\Provider\AttributeUpdaterProvider;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Webmozart\Assert\Assert;

/**
 */
class UpdateAttributeCommandHandler
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var AttributeUpdaterProvider
     */
    private AttributeUpdaterProvider $provider;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     * @param AttributeUpdaterProvider     $provider
     */
    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeUpdaterProvider $provider
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->provider = $provider;
    }

    /**
     * @param UpdateAttributeCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateAttributeCommand $command): void
    {
        $attribute = $this->attributeRepository->load($command->getId());

        Assert::notNull($attribute);

        $attribute->changeLabel($command->getLabel());
        $attribute->changeHint($command->getHint());
        $attribute->changePlaceholder($command->getPlaceholder());

        foreach ($command->getGroups() as $group) {
            $groupId = new AttributeGroupId($group);
            if (!$attribute->inGroup($groupId)) {
                $attribute->addGroup($groupId);
            }
        }

        foreach ($attribute->getGroups() as $groupId) {
            if (!\in_array($groupId->getValue(), $command->getGroups(), true)) {
                $attribute->removeGroup($groupId);
            }
        }

        $attributeType = new AttributeType($attribute->getType());
        $strategy = $this->provider->provide($attributeType);
        $attribute = $strategy->update($attribute, $command);
        $this->attributeRepository->save($attribute);
    }
}
