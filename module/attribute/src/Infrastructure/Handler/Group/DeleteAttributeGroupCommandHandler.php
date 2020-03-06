<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler\Group;

use Ergonode\Attribute\Domain\Command\Group\DeleteAttributeGroupCommand;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteAttributeGroupCommandHandler
{
    /**
     * @var AttributeGroupRepositoryInterface
     */
    private AttributeGroupRepositoryInterface $groupRepository;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var AttributeGroupQueryInterface
     */
    private AttributeGroupQueryInterface $query;

    /**
     * @param AttributeGroupRepositoryInterface $groupRepository
     * @param AttributeRepositoryInterface      $attributeRepository
     * @param AttributeGroupQueryInterface      $query
     */
    public function __construct(
        AttributeGroupRepositoryInterface $groupRepository,
        AttributeRepositoryInterface $attributeRepository,
        AttributeGroupQueryInterface $query
    ) {
        $this->groupRepository = $groupRepository;
        $this->attributeRepository = $attributeRepository;
        $this->query = $query;
    }

    /**
     * @param DeleteAttributeGroupCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteAttributeGroupCommand $command): void
    {
        $attributeIds = $this->query->getAllAttributes($command->getId());

        $attributeGroup = $this->groupRepository->load($command->getId());
        Assert::notNull($attributeGroup);

        foreach ($attributeIds as $attributeId) {
            $attribute = $this->attributeRepository->load($attributeId);
            Assert::notNull($attribute, sprintf('Attribute %s not exists', $attributeId->getValue()));
            $attribute->removeGroup($command->getId());
            $this->attributeRepository->save($attribute);
        }

        $this->groupRepository->delete($attributeGroup);
    }
}
