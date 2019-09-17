<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler;

use Ergonode\Attribute\Domain\Command\DeleteAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteAttributeCommandHandler
{
    /**
     * @var AttributeRepositoryInterface
     */
    private $repository;

    /**
     * @param AttributeRepositoryInterface $repository
     */
    public function __construct(AttributeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteAttributeCommand $command
     */
    public function __invoke(DeleteAttributeCommand $command)
    {
        $attribute = $this->repository->load($command->getId());
        Assert::isInstanceOf($attribute, AbstractAttribute::class, sprintf('Attribute with id "%s" not found', $command->getId()));

        $this->repository->delete($command->getId());
    }
}
