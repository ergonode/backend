<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler;

use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\Provider\AttributeFactoryProvider;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;

/**
 */
class CreateAttributeCommandHandler
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var AttributeFactoryProvider
     */
    private AttributeFactoryProvider $provider;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     * @param AttributeFactoryProvider     $provider
     */
    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeFactoryProvider $provider
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->provider = $provider;
    }

    /**
     * @param CreateAttributeCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateAttributeCommand $command): void
    {
        $strategy = $this->provider->provide($command->getType());
        $attribute = $strategy->create($command);

        foreach ($command->getGroups() as $group) {
            $attribute->addGroup(new AttributeGroupId($group));
        }

        $this->attributeRepository->save($attribute);
    }
}
