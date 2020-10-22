<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Infrastructure\Handler;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Editor\Domain\Command\RemoveProductAttributeValueCommand;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;

class RemoveProductAttributeValueCommandHandler extends AbstractValueCommandHandler
{
    /**
     * @var ProductDraftRepositoryInterface
     */
    private ProductDraftRepositoryInterface $repository;

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
     * @param AttributeRepositoryInterface    $attributeRepository
     * @param TokenStorageInterface           $tokenStorage
     */
    public function __construct(
        ProductDraftRepositoryInterface $repository,
        AttributeRepositoryInterface $attributeRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->repository = $repository;
        $this->attributeRepository = $attributeRepository;
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @param RemoveProductAttributeValueCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(RemoveProductAttributeValueCommand $command)
    {
        $language = $command->getLanguage();
        $draft = $this->repository->load($command->getId());
        $attributeId = $command->getAttributeId();
        $attribute = $this->attributeRepository->load($attributeId);

        Assert::notNull($draft);
        Assert::notNull($attribute);

        if (!$draft->hasAttribute($attribute->getCode())) {
            return;
        }

        $oldValue = $draft->getAttribute($attribute->getCode());
        $newValue = $this->calculate($oldValue, $language);

        if ($newValue && !empty($newValue->getValue())) {
            $draft->changeAttribute($attribute->getCode(), $newValue);
        } else {
            $draft->removeAttribute($attribute->getCode());
        }

        $token = $this->tokenStorage->getToken();
        if ($token) {
            /** @var User $user */
            $user = $token->getUser();
            $this->updateAudit($user, $draft);
        }

        $this->repository->save($draft);
    }

    /**
     * @param ValueInterface $value
     * @param Language       $language
     *
     * @return ValueInterface|null
     */
    public function calculate(ValueInterface $value, Language $language): ?ValueInterface
    {
        if ($value instanceof TranslatableStringValue) {
            $translation = $value->getValue();
            if (array_key_exists($language->getCode(), $translation)) {
                unset($translation[$language->getCode()]);
            }

            return new TranslatableStringValue(new TranslatableString($translation));
        }

        if ($value instanceof StringCollectionValue) {
            $translation = $value->getValue();
            if (array_key_exists($language->getCode(), $translation)) {
                unset($translation[$language->getCode()]);
            }

            return new StringCollectionValue($translation);
        }

        return null;
    }
}
