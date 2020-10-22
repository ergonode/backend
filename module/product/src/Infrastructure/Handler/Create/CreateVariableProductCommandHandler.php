<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler\Create;

use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Command\Create\CreateVariableProductCommand;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Symfony\Component\Security\Core\Security;
use Ergonode\Product\Infrastructure\Handler\AbstractCreateProductHandler;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;

class CreateVariableProductCommandHandler extends AbstractCreateProductHandler
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @param ProductRepositoryInterface   $productRepository
     * @param AttributeRepositoryInterface $attributeRepository
     * @param TokenStorageInterface        $tokenStorage
     * @param WorkflowProvider             $provider
     * @param LanguageQueryInterface       $query
     * @param Security                     $security
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        AttributeRepositoryInterface $attributeRepository,
        TokenStorageInterface $tokenStorage,
        WorkflowProvider $provider,
        LanguageQueryInterface $query,
        Security $security
    ) {
        parent::__construct($productRepository, $tokenStorage, $provider, $query, $security);
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param CreateVariableProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateVariableProductCommand $command)
    {
        $attributes = $command->getAttributes();

        $attributes = $this->addAudit($attributes);
        $attributes = $this->addStatusAttribute($attributes);

        $product = new VariableProduct(
            $command->getId(),
            $command->getSku(),
            $command->getTemplateId(),
            $command->getCategories(),
            $attributes,
        );

        $this->productRepository->save($product);
    }
}
