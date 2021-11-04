<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Option;

use Ergonode\Attribute\Domain\Command\Option\DeleteOptionCommand;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Core\Infrastructure\Exception\ExistingRelationshipsException;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;

class DeleteOptionCommandHandler
{
    private OptionRepositoryInterface $optionRepository;

    private AttributeRepositoryInterface $attributeRepository;

    private RelationshipsResolverInterface $relationshipsResolver;

    public function __construct(
        OptionRepositoryInterface $optionRepository,
        AttributeRepositoryInterface $attributeRepository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->optionRepository = $optionRepository;
        $this->attributeRepository = $attributeRepository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteOptionCommand $command): void
    {
        /** @var AbstractOptionAttribute $attribute */
        $attribute = $this->attributeRepository->load($command->getAttributeId());
        Assert::isInstanceOf($attribute, AbstractOptionAttribute::class);
        $option = $this->optionRepository->load($command->getId());

        Assert::isInstanceOf(
            $option,
            AbstractOption::class,
            sprintf('Option with ID "%s" not found', $command->getId())
        );
        $relationships = $this->relationshipsResolver->resolve($command->getId());

        if (null !== $relationships) {
            throw new ExistingRelationshipsException($command->getId());
        }
        $attribute->removeOption($option);

        $this->attributeRepository->save($attribute);
        $this->optionRepository->delete($option);
    }
}
