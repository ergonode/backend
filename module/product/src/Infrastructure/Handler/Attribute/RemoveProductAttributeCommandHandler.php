<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Attribute;

use Ergonode\Account\Application\Security\Security;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Command\Attribute\RemoveProductAttributeCommand;

class RemoveProductAttributeCommandHandler extends AbstractValueCommandHandler
{
    private ProductRepositoryInterface $repository;

    private AttributeRepositoryInterface $attributeRepository;

    private Security $security;

    public function __construct(
        ProductRepositoryInterface $repository,
        AttributeRepositoryInterface $attributeRepository,
        Security $security
    ) {
        $this->repository = $repository;
        $this->attributeRepository = $attributeRepository;
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

        $oldValue = $product->getAttribute($attribute->getCode());
        $newValue = $this->calculate($oldValue, $language);

        if ($newValue && !empty($newValue->getValue())) {
            $product->changeAttribute($attribute->getCode(), $newValue);
        } else {
            $product->removeAttribute($attribute->getCode());
        }

        $user = $this->security->getUser();
        if ($user) {
            $this->updateAudit($user, $product);
        }

        $this->repository->save($product);
    }

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
