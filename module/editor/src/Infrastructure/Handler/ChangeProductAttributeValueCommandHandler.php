<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Editor\Infrastructure\Handler;

use Ergonode\Account\Application\Security\Security;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Editor\Domain\Command\ChangeProductAttributeValueCommand;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\Value\Domain\Service\ValueManipulationService;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Infrastructure\Mapper\AttributeValueMapper;

class ChangeProductAttributeValueCommandHandler extends AbstractValueCommandHandler
{
    private ProductDraftRepositoryInterface $repository;

    private ValueManipulationService $service;

    private AttributeRepositoryInterface $attributeRepository;

    private Security $security;

    private AttributeValueMapper $mapper;

    public function __construct(
        ProductDraftRepositoryInterface $repository,
        ValueManipulationService $service,
        AttributeRepositoryInterface $attributeRepository,
        Security $security,
        AttributeValueMapper $mapper
    ) {
        $this->repository = $repository;
        $this->service = $service;
        $this->attributeRepository = $attributeRepository;
        $this->security = $security;
        $this->mapper = $mapper;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(ChangeProductAttributeValueCommand $command): void
    {
        $language = $command->getLanguage();
        $draft = $this->repository->load($command->getId());
        $attributeId = $command->getAttributeId();
        $attribute = $this->attributeRepository->load($attributeId);

        Assert::notNull($draft);
        Assert::notNull($attribute);

        $newValue = $this->mapper->map($attribute, [$language->getCode() => $command->getValue()]);

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

        $user = $this->security->getUser();
        if ($user) {
            $this->updateAudit($user, $draft);
        }

        $this->repository->save($draft);
    }
}
