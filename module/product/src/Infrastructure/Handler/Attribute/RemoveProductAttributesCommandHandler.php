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
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Product\Domain\Updater\ProductAttributeUpdater;
use Ergonode\Product\Domain\Command\Attribute\RemoveProductAttributesCommand;
use Ergonode\Core\Domain\ValueObject\Language;

class RemoveProductAttributesCommandHandler extends AbstractValueCommandHandler
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
    public function __invoke(RemoveProductAttributesCommand $command): void
    {
        $product = $this->repository->load($command->getId());
        Assert::notNull($product);

        foreach ($command->getAttributes() as $id => $languages) {
            $attribute = $this->attributeRepository->load(new AttributeId($id));
            Assert::notNull($attribute);
            $value = [];
            /** @var Language $language */
            foreach ($languages as $language) {
                $value[$language->getCode()] = null;
            }

            $product = $this->updater->remove($product, $attribute, $value);
        }

        $this->repository->save($product);
    }
}
