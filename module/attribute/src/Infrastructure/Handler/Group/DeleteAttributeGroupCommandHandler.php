<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler\Group;

use Ergonode\Attribute\Domain\Command\Group\DeleteAttributeGroupCommand;
use Ergonode\Attribute\Domain\Command\Group\UpdateAttributeGroupCommand;
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteAttributeGroupCommandHandler
{
    /**
     * @var AttributeGroupRepositoryInterface
     */
    private $repository;

    /**
     * @param AttributeGroupRepositoryInterface $repository
     */
    public function __construct(AttributeGroupRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteAttributeGroupCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteAttributeGroupCommand $command): void
    {
        $attributeGroup = $this->repository->load($command->getId());
        Assert::notNull($attributeGroup);

        $this->repository->delete($attributeGroup);
    }
}
