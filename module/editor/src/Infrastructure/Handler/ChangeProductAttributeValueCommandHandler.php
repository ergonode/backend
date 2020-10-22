<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Infrastructure\Handler;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Editor\Domain\Command\ChangeProductAttributeValueCommand;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\Value\Domain\Service\ValueManipulationService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

class ChangeProductAttributeValueCommandHandler extends AbstractValueCommandHandler
{
    /**
     * @var ProductDraftRepositoryInterface
     */
    private ProductDraftRepositoryInterface $repository;

    /**
     * @var ValueManipulationService
     */
    private ValueManipulationService $service;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * @param ProductDraftRepositoryInterface $repository
     * @param ValueManipulationService        $service
     * @param AttributeRepositoryInterface    $attributeRepository
     * @param TokenStorageInterface           $tokenStorage
     */
    public function __construct(
        ProductDraftRepositoryInterface $repository,
        ValueManipulationService $service,
        AttributeRepositoryInterface $attributeRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->repository = $repository;
        $this->service = $service;
        $this->attributeRepository = $attributeRepository;
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @param ChangeProductAttributeValueCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ChangeProductAttributeValueCommand $command)
    {
        $language = $command->getLanguage();
        $draft = $this->repository->load($command->getId());
        $attributeId = $command->getAttributeId();
        $attribute = $this->attributeRepository->load($attributeId);

        Assert::notNull($draft);
        Assert::notNull($attribute);

        $newValue = $this->createValue($language, $attribute, $command->getValue());

        if ($draft->hasAttribute($attribute->getCode())) {
            if (null === $newValue) {
                $draft->removeAttribute($attribute->getCode());
            } else {
                $oldValue = $draft->getAttribute($attribute->getCode());
                $calculatedValue = $this->service->calculate($oldValue, $newValue);
                $draft->changeAttribute($attribute->getCode(), $calculatedValue);
            }
        } else {
            $draft->addAttribute($attribute->getCode(), $newValue);
        }

        $token = $this->tokenStorage->getToken();
        if ($token) {
            /** @var User $user */
            $user = $token->getUser();
            $this->updateAudit($user, $draft);
        }

        $this->repository->save($draft);
    }
}
