<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Group;

use Ergonode\Attribute\Domain\Command\Group\UpdateAttributeGroupCommand;
use Ergonode\Attribute\Domain\Entity\AttributeGroup;
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;
use Webmozart\Assert\Assert;

class UpdateAttributeGroupCommandHandler
{
    private AttributeGroupRepositoryInterface $repository;

    public function __construct(AttributeGroupRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(UpdateAttributeGroupCommand $command): void
    {
        $attributeGroup = $this->repository->load($command->getId());
        Assert::notNull($attributeGroup);
        if (!$attributeGroup instanceof AttributeGroup) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    AttributeGroup::class,
                    get_debug_type($attributeGroup)
                )
            );
        }

        $attributeGroup->changeName($command->getName());

        $this->repository->save($attributeGroup);
    }
}
