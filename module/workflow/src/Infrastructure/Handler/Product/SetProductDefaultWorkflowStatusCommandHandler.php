<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Product;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Workflow\Domain\Provider\WorkflowProviderInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Updater\ProductAttributeUpdater;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Command\Product\SetProductDefaultWorkflowStatusCommand;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;

class SetProductDefaultWorkflowStatusCommandHandler
{
    private AttributeQueryInterface $query;

    private WorkflowProviderInterface $provider;

    private AttributeRepositoryInterface $attributeRepository;

    private ProductRepositoryInterface $productRepository;

    private ProductAttributeUpdater $updater;

    public function __construct(
        AttributeQueryInterface $query,
        WorkflowProviderInterface $provider,
        AttributeRepositoryInterface $attributeRepository,
        ProductRepositoryInterface $productRepository,
        ProductAttributeUpdater $updater
    ) {
        $this->query = $query;
        $this->provider = $provider;
        $this->attributeRepository = $attributeRepository;
        $this->productRepository = $productRepository;
        $this->updater = $updater;
    }

    public function __invoke(SetProductDefaultWorkflowStatusCommand $command): void
    {
        $language = $command->getLanguage();
        $product = $this->productRepository->load($command->getId());
        Assert::isInstanceOf($product, AbstractProduct::class);
        $attributeId = $this->query->findAttributeIdByCode(new AttributeCode(StatusSystemAttribute::CODE));
        Assert::isInstanceOf($attributeId, AttributeId::class);
        $attribute = $this->attributeRepository->load($attributeId);
        Assert::isInstanceOf($attribute, AbstractAttribute::class);
        $workflow = $this->provider->provide($language);
        $status = $workflow->getDefaultStatus();

        $this->updater->update($product, $attribute, [$language->getCode() => $status->getValue()]);

        $this->productRepository->save($product);
    }
}
