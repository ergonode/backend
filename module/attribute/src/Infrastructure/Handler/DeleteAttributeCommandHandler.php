<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler;

use Ergonode\Attribute\Domain\Command\DeleteAttributeCommand;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Application\Exception\NotImplementedException;
use Webmozart\Assert\Assert;

/**
 */
class DeleteAttributeCommandHandler
{
    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param DeleteAttributeCommand $command
     *
     * @throws NotImplementedException
     */
    public function __invoke(DeleteAttributeCommand $command)
    {
        $attribute = $this->attributeRepository->load($command->getId());

        Assert::notNull($attribute);

        //@todo add remove attribute...
        throw new NotImplementedException('Add attribute remove in future');
    }
}
