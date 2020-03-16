<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler\Group;

use Ergonode\Attribute\Domain\Command\Group\UpdateAttributeGroupCommand;
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class UpdateAttributeGroupCommandHandler
{
    /**
     * @var AttributeGroupRepositoryInterface
     */
    private AttributeGroupRepositoryInterface $repository;

    /**
     * @param AttributeGroupRepositoryInterface $repository
     */
    public function __construct(AttributeGroupRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateAttributeGroupCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateAttributeGroupCommand $command): void
    {
        $attributeGroup = $this->repository->load($command->getId());

        Assert::notNull($attributeGroup);

        $attributeGroup->changeName($command->getName());

        $this->repository->save($attributeGroup);
    }
}
