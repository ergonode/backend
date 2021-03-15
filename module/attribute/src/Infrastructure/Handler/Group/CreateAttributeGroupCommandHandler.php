<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Group;

use Ergonode\Attribute\Domain\Command\Group\CreateAttributeGroupCommand;
use Ergonode\Attribute\Domain\Factory\Group\AttributeGroupFactory;
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;

class CreateAttributeGroupCommandHandler
{
    private AttributeGroupFactory $factory;

    private AttributeGroupRepositoryInterface $repository;

    public function __construct(AttributeGroupFactory $factory, AttributeGroupRepositoryInterface $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateAttributeGroupCommand $command): void
    {
        $attributeGroup = $this->factory->create(
            $command->getId(),
            $command->getCode(),
            $command->getName()
        );

        $this->repository->save($attributeGroup);
    }
}
