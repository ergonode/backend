<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Attribute;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Updater\ProductAttributeUpdater;
use Ergonode\Product\Domain\Command\Attribute\ChangeProductAttributeCommand;

class ChangeProductAttributeCommandHandler extends AbstractValueCommandHandler
{
    private ProductRepositoryInterface $repository;

    private AttributeRepositoryInterface $attributeRepository;

    private ProductAttributeUpdater $updater;

    public function __construct(
        ProductRepositoryInterface $repository,
        AttributeRepositoryInterface $attributeRepository,
        ProductAttributeUpdater $updater
    ) {
        $this->repository = $repository;
        $this->attributeRepository = $attributeRepository;
        $this->updater = $updater;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(ChangeProductAttributeCommand $command): void
    {
        $language = $command->getLanguage();
        $product = $this->repository->load($command->getId());
        $attributeId = $command->getAttributeId();
        $attribute = $this->attributeRepository->load($attributeId);

        Assert::notNull($product);
        Assert::notNull($attribute);

        $this->updater->update($product, $attribute, [$language->getCode() => $command->getValue()]);

        $this->repository->save($product);
    }
}
