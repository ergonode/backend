<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Infrastructure\Handler;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Editor\Domain\Command\ChangeProductAttributeValueCommand;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\EditedBySystemAttribute;
use Ergonode\Value\Domain\Service\ValueManipulationService;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

/**
 */
class ChangeProductAttributeValueCommandHandler
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
        /** @var ProductDraft $draft */
        $language = $command->getLanguage();
        $draft = $this->repository->load($command->getId());
        $attributeId = $command->getAttributeId();
        $attribute = $this->attributeRepository->load($attributeId);

        Assert::notNull($draft);
        Assert::notNull($attribute);

        $newValue = $this->createValue($language, $attribute, $command->getValue());

        if ($newValue) {
            if ($draft->hasAttribute($attribute->getCode())) {
                $oldValue = $draft->getAttribute($attribute->getCode());
                $calculatedValue = $this->service->calculate($oldValue, $newValue);
                $draft->changeAttribute($attribute->getCode(), $calculatedValue);
            } else {
                $draft->addAttribute($attribute->getCode(), $newValue);
            }
        } elseif ($draft->hasAttribute($attribute->getCode())) {
            $draft->removeAttribute($attribute->getCode());
        }

        $token = $this->tokenStorage->getToken();
        if ($token) {
            $updatedAt = new \DateTime();
            $editedByCode = new AttributeCode(EditedBySystemAttribute::CODE);
            $editedAtCode = new AttributeCode(EditedAtSystemAttribute::CODE);
            /** @var User $user */
            $user = $token->getUser();
            $editedByValue = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
            $editedAtValue = new StringValue($updatedAt->format('Y-m-d H:i:s'));
            $this->attributeUpdate($draft, $editedByCode, $editedByValue);
            $this->attributeUpdate($draft, $editedAtCode, $editedAtValue);
        }

        $this->repository->save($draft);
    }

    /**
     * @param Language          $language
     * @param AbstractAttribute $attribute
     * @param mixed             $value
     *
     * @return ValueInterface|null
     *
     * @todo Require key for collections and multi collections ....
     */
    private function createValue(Language $language, AbstractAttribute $attribute, $value): ?ValueInterface
    {
        if (null === $value || '' === $value) {
            return null;
        }

        if ($attribute instanceof MultiSelectAttribute) {
            return new StringCollectionValue($value);
        }

        if ($attribute instanceof SelectAttribute) {
            return new StringValue((string) $value);
        }

        if ($attribute->isMultilingual()) {
            return new TranslatableStringValue(new TranslatableString([$language->getCode() => $value]));
        }

        return new StringValue((string) $value);
    }

    /**
     * @param ProductDraft   $product
     * @param AttributeCode  $code
     * @param ValueInterface $value
     *
     * @throws \Exception
     */
    private function attributeUpdate(ProductDraft $product, AttributeCode $code, ValueInterface $value): void
    {
        if (!$product->hasAttribute($code)) {
            $product->addAttribute($code, $value);
        } else {
            $product->changeAttribute($code, $value);
        }
    }
}
