<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Attribute;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Application\Security\Security;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Command\Attribute\RemoveProductAttributeCommand;
use Ergonode\Product\Domain\Updater\ProductAttributeUpdater;

class RemoveProductAttributeCommandHandler extends AbstractValueCommandHandler
{
    private ProductRepositoryInterface $repository;

    private AttributeRepositoryInterface $attributeRepository;

    private ProductAttributeUpdater $updater;

    private Security $security;

    public function __construct(
        ProductRepositoryInterface $repository,
        AttributeRepositoryInterface $attributeRepository,
        ProductAttributeUpdater $updater,
        Security $security
    ) {
        $this->repository = $repository;
        $this->attributeRepository = $attributeRepository;
        $this->updater = $updater;
        $this->security = $security;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(RemoveProductAttributeCommand $command): void
    {
        $language = $command->getLanguage();
        $product = $this->repository->load($command->getId());
        $attributeId = $command->getAttributeId();
        $attribute = $this->attributeRepository->load($attributeId);

        Assert::notNull($product);
        Assert::notNull($attribute);

        if (!$product->hasAttribute($attribute->getCode())) {
            return;
        }

        $value[$language->getCode()] = null;

        $this->updater->remove($product, $attribute, $value);

        $user = $this->security->getUser();
        if ($user) {
            $this->updateAudit($user, $product);
        }

        $this->repository->save($product);
    }
}
