<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Handler\Import;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ImporterErgonode1\Domain\Command\Import\ImportAttributeCommand;
use Ergonode\ImporterErgonode1\Infrastructure\Model\AttributeParametersModel;
use Ergonode\ImporterErgonode1\Infrastructure\Resolver\AttributeFactoryResolver;

class ImportAttributeCommandHandler
{
    private AttributeRepositoryInterface $attributeRepository;
    private AttributeFactoryResolver $attributeFactoryResolver;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeFactoryResolver $attributeFactoryResolver
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->attributeFactoryResolver = $attributeFactoryResolver;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(ImportAttributeCommand $command): void
    {
        $attribute = $this->attributeRepository->load($command->getId());

        if (null === $attribute) {
            $factory = $this->attributeFactoryResolver->resolve($command->getType());
            $attribute = $factory->create(
                $command->getId(),
                $command->getCode(),
                $command->getScope(),
                new TranslatableString($command->getLabel()),
                new TranslatableString($command->getHint()),
                new TranslatableString($command->getPlaceholder()),
                new AttributeParametersModel($command->getParameters())
            );
        } else {
            $attribute->changeLabel(new TranslatableString($command->getLabel()));
            $attribute->changeHint(new TranslatableString($command->getHint()));
            $attribute->changePlaceholder(new TranslatableString($command->getPlaceholder()));
            $attribute->changeScope($command->getScope());
        }

        $this->attributeRepository->save($attribute);
    }
}
