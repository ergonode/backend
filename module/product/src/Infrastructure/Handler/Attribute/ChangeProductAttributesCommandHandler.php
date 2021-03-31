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
use Ergonode\Product\Domain\Command\Attribute\ChangeProductAttributesCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Product\Domain\Updater\ProductAttributeUpdater;

class ChangeProductAttributesCommandHandler extends AbstractValueCommandHandler
{
    private ProductRepositoryInterface $repository;

    private AttributeRepositoryInterface $attributeRepository;

    private Security $security;

    private ProductAttributeUpdater $updater;

    public function __construct(
        ProductRepositoryInterface $repository,
        AttributeRepositoryInterface $attributeRepository,
        Security $security,
        ProductAttributeUpdater $updater
    ) {
        $this->repository = $repository;
        $this->attributeRepository = $attributeRepository;
        $this->security = $security;
        $this->updater = $updater;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(ChangeProductAttributesCommand $command): void
    {
        $product = $this->repository->load($command->getId());
        Assert::notNull($product);

        foreach ($command->getAttributes() as $id => $value) {
            $attribute = $this->attributeRepository->load(new AttributeId($id));
            Assert::notNull($attribute);
            $product = $this->updater->update($product, $attribute, $value);
        }

        $user = $this->security->getUser();
        if ($user) {
            $this->updateAudit($user, $product);
        }

        $this->repository->save($product);
    }
}
