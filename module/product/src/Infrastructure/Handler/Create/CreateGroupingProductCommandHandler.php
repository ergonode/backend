<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler\Create;

use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Designer\Domain\Entity\Attribute\TemplateSystemAttribute;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Product\Domain\Entity\Attribute\CreatedAtSystemAttribute;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Product\Domain\Entity\Attribute\CreatedBySystemAttribute;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Product\Domain\Entity\GroupingProduct;
use Ergonode\Product\Domain\Command\Create\CreateGroupingProductCommand;

/**
 */
class CreateGroupingProductCommandHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface   $tokenStorage;

    /**
     * @var WorkflowProvider
     */
    private WorkflowProvider $provider;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param TokenStorageInterface      $tokenStorage
     * @param WorkflowProvider           $provider
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        TokenStorageInterface $tokenStorage,
        WorkflowProvider $provider
    ) {
        $this->productRepository = $productRepository;
        $this->tokenStorage = $tokenStorage;
        $this->provider = $provider;
    }

    /**
     * @param CreateGroupingProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CreateGroupingProductCommand $command)
    {
        $attributes = $command->getAttributes();
        $createdAt = new \DateTime();

        $attributes[TemplateSystemAttribute::CODE] = new StringValue($command->getTemplateId()->getValue());
        $attributes[CreatedAtSystemAttribute::CODE] = new StringValue($createdAt->format('Y-m-d H:i:s'));
        $token = $this->tokenStorage->getToken();
        if ($token) {
            /** @var User $user */
            $user = $token->getUser();
            $value = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
            $attributes[CreatedBySystemAttribute::CODE] = $value;
        }
        $workflow = $this->provider->provide();
        $attributes[StatusSystemAttribute::CODE] = new StringValue($workflow->getDefaultStatus()->getValue());

        $product = new GroupingProduct(
            $command->getId(),
            $command->getSku(),
            $command->getCategories(),
            $attributes,
        );

        $this->productRepository->save($product);
    }
}
