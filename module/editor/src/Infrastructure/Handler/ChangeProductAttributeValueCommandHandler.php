<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Infrastructure\Handler;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Editor\Domain\Command\ChangeProductAttributeValueCommand;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\Value\Domain\Service\ValueManipulationService;
use Ergonode\Value\Domain\ValueObject\CollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableCollectionValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Webmozart\Assert\Assert;

/**
 */
class ChangeProductAttributeValueCommandHandler
{
    /**
     * @var ProductDraftRepositoryInterface
     */
    private $repository;

    /**
     * @var ValueManipulationService
     */
    private $service;

    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @param ProductDraftRepositoryInterface $repository
     * @param ValueManipulationService        $service
     * @param AttributeRepositoryInterface    $attributeRepository
     */
    public function __construct(ProductDraftRepositoryInterface $repository, ValueManipulationService $service, AttributeRepositoryInterface $attributeRepository)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->attributeRepository = $attributeRepository;
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
        if ($value === null || $value === '') {
            return null;
        }

        if ($attribute instanceof MultiSelectAttribute) {
            return new CollectionValue($value);
        }

        if ($attribute->isMultilingual()) {
            return new TranslatableStringValue(new TranslatableString([$language->getCode() => $value]));
        }

        return new StringValue($value);
    }
}
