<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Editor\Infrastructure\Handler;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Editor\Domain\Command\ChangeProductAttributeValueCommand;
use Ergonode\Value\Domain\Service\ValueManipulationService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Infrastructure\Mapper\AttributeValueMapper;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;

class ChangeProductAttributeValueCommandHandler extends AbstractValueCommandHandler
{
    private ProductRepositoryInterface $repository;

    private ValueManipulationService $service;

    private AttributeRepositoryInterface $attributeRepository;

    private TokenStorageInterface $tokenStorage;

    private AttributeValueMapper $mapper;

    public function __construct(
        ProductRepositoryInterface $repository,
        ValueManipulationService $service,
        AttributeRepositoryInterface $attributeRepository,
        TokenStorageInterface $tokenStorage,
        AttributeValueMapper $mapper
    ) {
        $this->repository = $repository;
        $this->service = $service;
        $this->attributeRepository = $attributeRepository;
        $this->tokenStorage = $tokenStorage;
        $this->mapper = $mapper;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(ChangeProductAttributeValueCommand $command): void
    {
        $language = $command->getLanguage();
        $product = $this->repository->load($command->getId());
        $attributeId = $command->getAttributeId();
        $attribute = $this->attributeRepository->load($attributeId);

        Assert::notNull($product);
        Assert::notNull($attribute);

        $newValue = $this->mapper->map($attribute, [$language->getCode() => $command->getValue()]);

        if ($product->hasAttribute($attribute->getCode())) {
            if (null === $newValue) {
                $product->removeAttribute($attribute->getCode());
            } else {
                $oldValue = $product->getAttribute($attribute->getCode());
                $calculatedValue = $this->service->calculate($oldValue, $newValue);
                $product->changeAttribute($attribute->getCode(), $calculatedValue);
            }
        } else {
            $product->addAttribute($attribute->getCode(), $newValue);
        }

        $token = $this->tokenStorage->getToken();
        if ($token) {
            /** @var User $user */
            $user = $token->getUser();
            $this->updateAudit($user, $product);
        }

        $this->repository->save($product);
    }
}
