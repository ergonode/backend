<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupClient;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupOptionClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

/**
 */
abstract class AbstractPropertyOptionSynchronizer implements SynchronizerInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    protected AttributeRepositoryInterface $attributeRepository;

    /**
     * @var Shopware6PropertyGroupRepositoryInterface
     */
    protected Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository;

    /**
     * @var Shopware6PropertyGroupClient
     */
    protected Shopware6PropertyGroupClient $propertyGroupClient;

    /**
     * @var OptionQueryInterface
     */
    protected OptionQueryInterface  $optionQuery;

    /**
     * @var OptionRepositoryInterface
     */
    protected OptionRepositoryInterface  $optionRepository;

    /**
     * @var Shopware6PropertyGroupOptionClient
     */
    protected Shopware6PropertyGroupOptionClient $propertyGroupOptionClient;

    /**
     * @param AttributeRepositoryInterface              $attributeRepository
     * @param Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository
     * @param Shopware6PropertyGroupClient              $propertyGroupClient
     * @param OptionQueryInterface                      $optionQuery
     * @param OptionRepositoryInterface                 $optionRepository
     * @param Shopware6PropertyGroupOptionClient        $propertyGroupOptionClient
     */
    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository,
        Shopware6PropertyGroupClient $propertyGroupClient,
        OptionQueryInterface $optionQuery,
        OptionRepositoryInterface $optionRepository,
        Shopware6PropertyGroupOptionClient $propertyGroupOptionClient
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->propertyGroupRepository = $propertyGroupRepository;
        $this->propertyGroupClient = $propertyGroupClient;
        $this->optionQuery = $optionQuery;
        $this->optionRepository = $optionRepository;
        $this->propertyGroupOptionClient = $propertyGroupOptionClient;
    }

    /**
     * @param Shopware6Channel  $channel
     * @param AbstractAttribute $attribute
     */
    protected function checkExistOrCreate(Shopware6Channel $channel, AbstractAttribute $attribute): void
    {
        $isset = $this->propertyGroupRepository->exists($channel->getId(), $attribute->getId());
        if ($isset) {
            return;
        }
        $this->createShopwarePropertyGroup($channel, $attribute);
    }

    /**
     * @param Shopware6Channel  $channel
     * @param AbstractAttribute $attribute
     */
    protected function checkOrCreateWithOptions(Shopware6Channel $channel, AbstractAttribute $attribute): void
    {
        $this->checkExistOrCreate($channel, $attribute);
        $options = $this->optionQuery->getOptions($attribute->getId());
        foreach ($options as $option) {
            $optionId = new AggregateId($option);
            $this->synchronizeOption($channel, $attribute->getId(), $optionId);
        }
    }

    /**
     * @param Shopware6Channel  $channel
     * @param AbstractAttribute $attribute
     */
    private function createShopwarePropertyGroup(Shopware6Channel $channel, AbstractAttribute $attribute): void
    {
        $name = $attribute->getLabel()->get($channel->getDefaultLanguage());

        $propertyGroup = new Shopware6PropertyGroup(
            null,
            $name ?: $attribute->getCode()->getValue()
        );

        foreach ($channel->getLanguages() as $language) {
            if ($attribute->getLabel()->has($language)) {
                $label = $attribute->getLabel()->get($language);
                $propertyGroup->addTranslations($language, 'name', $label);
            }
        }
        $new = $this->propertyGroupClient->createPropertyGroupResource($channel, $propertyGroup);
        Assert::notNull($new);

        $this->propertyGroupRepository->save(
            $channel->getId(),
            $attribute->getId(),
            $new->getId(),
            $attribute->getType()
        );
    }

    /**
     * @param Shopware6Channel $channel
     * @param AttributeId      $attributeId
     * @param AggregateId      $optionId
     */
    private function synchronizeOption(
        Shopware6Channel $channel,
        AttributeId $attributeId,
        AggregateId $optionId
    ): void {
        $option = $this->optionRepository->load($optionId);
        Assert::notNull($option);
        $propertyGroupOptions = $this->createPropertyGroupOptionsFromOptions($channel, $option);

        $this->propertyGroupOptionClient->findByNameOrCreate(
            $channel,
            $attributeId,
            $propertyGroupOptions
        );
    }

    /**
     * @param Shopware6Channel $channel
     * @param AbstractOption   $option
     *
     * @return Shopware6PropertyGroupOption
     */
    private function createPropertyGroupOptionsFromOptions(
        Shopware6Channel $channel,
        AbstractOption $option
    ): Shopware6PropertyGroupOption {
        $name = $option->getLabel()->get($channel->getDefaultLanguage());

        $propertyGroupOption = new Shopware6PropertyGroupOption(null, $name ?: $option->getCode()->getValue());

        foreach ($channel->getLanguages() as $language) {
            $calculateValue = $option->getLabel()->get($language);
            if ($calculateValue) {
                $propertyGroupOption->addTranslations($language, 'name', $calculateValue);
            }
        }

        return $propertyGroupOption;
    }
}
