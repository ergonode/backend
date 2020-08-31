<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupOptionsRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Builder\Shopware6PropertyGroupOptionBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupOptionClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\AggregateId;
use GuzzleHttp\Exception\ClientException;
use Webmozart\Assert\Assert;

/**
 */
class PropertyGroupOptionsShopware6ExportProcess
{
    /**
     * @var Shopware6PropertyGroupRepositoryInterface
     */
    protected Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository;

    /**
     * @var OptionQueryInterface
     */
    protected OptionQueryInterface  $optionQuery;

    /**
     * @var Shopware6PropertyGroupOptionsRepositoryInterface
     */
    private Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository;

    /**
     * @var Shopware6PropertyGroupOptionClient
     */
    private Shopware6PropertyGroupOptionClient $propertyGroupOptionClient;

    /**
     * @var Shopware6PropertyGroupOptionBuilder
     */
    private Shopware6PropertyGroupOptionBuilder $builder;

    /**
     * @var OptionRepositoryInterface
     */
    private OptionRepositoryInterface $optionRepository;

    /**
     * @var Shopware6LanguageRepositoryInterface
     */
    private Shopware6LanguageRepositoryInterface  $languageRepository;

    /**
     * @param Shopware6PropertyGroupRepositoryInterface        $propertyGroupRepository
     * @param OptionQueryInterface                             $optionQuery
     * @param Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository
     * @param Shopware6PropertyGroupOptionClient               $propertyGroupOptionClient
     * @param Shopware6PropertyGroupOptionBuilder              $builder
     * @param OptionRepositoryInterface                        $optionRepository
     * @param Shopware6LanguageRepositoryInterface             $languageRepository
     */
    public function __construct(
        Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository,
        OptionQueryInterface $optionQuery,
        Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository,
        Shopware6PropertyGroupOptionClient $propertyGroupOptionClient,
        Shopware6PropertyGroupOptionBuilder $builder,
        OptionRepositoryInterface $optionRepository,
        Shopware6LanguageRepositoryInterface $languageRepository
    ) {
        $this->propertyGroupRepository = $propertyGroupRepository;
        $this->optionQuery = $optionQuery;
        $this->propertyGroupOptionsRepository = $propertyGroupOptionsRepository;
        $this->propertyGroupOptionClient = $propertyGroupOptionClient;
        $this->builder = $builder;
        $this->optionRepository = $optionRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @param ExportId          $id
     * @param Shopware6Channel  $channel
     * @param AbstractAttribute $attribute
     */
    public function process(ExportId $id, Shopware6Channel $channel, AbstractAttribute $attribute): void
    {
        $propertyGroupId = $this->propertyGroupRepository->load($channel->getId(), $attribute->getId());
        Assert::notNull($propertyGroupId);

        $options = $this->optionQuery->getOptions($attribute->getId());
        foreach ($options as $option) {
            $optionId = new AggregateId($option);
            $option = $this->optionRepository->load($optionId);
            Assert::notNull($option);
            $this->processOptions($channel, $propertyGroupId, $attribute, $option);
        }
    }

    /**
     * @param Shopware6Channel  $channel
     * @param string            $propertyGroupId
     * @param AbstractAttribute $attribute
     * @param AbstractOption    $option
     */
    private function processOptions(
        Shopware6Channel $channel,
        string $propertyGroupId,
        AbstractAttribute $attribute,
        AbstractOption $option
    ): void {
        $propertyGroupOption = $this->loadPropertyGroupOption(
            $channel,
            $attribute->getId(),
            $option->getId(),
            $propertyGroupId
        );

        if ($propertyGroupOption) {
            $this->updatePropertyGroupOption($channel, $propertyGroupOption, $propertyGroupId, $option);
        } else {
            $propertyGroupOption = new Shopware6PropertyGroupOption();
            $this->builder->build($channel, $propertyGroupOption, $option);
            $this->propertyGroupOptionClient->insert($channel, $propertyGroupId, $propertyGroupOption, $option);
        }
        foreach ($channel->getLanguages() as $language) {
            if ($this->languageRepository->exists($channel->getId(), $language->getCode())) {
                $this->updatePropertyGroupOptionWithLanguage($channel, $language, $propertyGroupId, $option);
            }
        }
    }

    /**
     * @param Shopware6Channel             $channel
     * @param Shopware6PropertyGroupOption $propertyGroupOption
     * @param string                       $propertyGroupId
     * @param AbstractOption               $option
     * @param Language|null                $language
     * @param Shopware6Language|null       $shopwareLanguage
     */
    private function updatePropertyGroupOption(
        Shopware6Channel $channel,
        Shopware6PropertyGroupOption $propertyGroupOption,
        string $propertyGroupId,
        AbstractOption $option,
        ?Language $language = null,
        ?Shopware6Language $shopwareLanguage = null
    ): void {
        $this->builder->build($channel, $propertyGroupOption, $option, $language);
        if ($propertyGroupOption->isModified()) {
            $this->propertyGroupOptionClient->update(
                $channel,
                $propertyGroupId,
                $propertyGroupOption,
                $shopwareLanguage
            );
        }
    }

    /**
     * @param Shopware6Channel $channel
     * @param Language         $language
     * @param string           $propertyGroupId
     * @param AbstractOption   $option
     */
    private function updatePropertyGroupOptionWithLanguage(
        Shopware6Channel $channel,
        Language $language,
        string $propertyGroupId,
        AbstractOption $option
    ): void {
        $shopwareLanguage = $this->languageRepository->load($channel->getId(), $language->getCode());
        Assert::notNull($shopwareLanguage);

        $shopwarePropertyGroupOption = $this->loadPropertyGroupOption(
            $channel,
            $option->getAttributeId(),
            $option->getId(),
            $propertyGroupId,
            $shopwareLanguage
        );
        Assert::notNull($shopwarePropertyGroupOption);

        $this->updatePropertyGroupOption(
            $channel,
            $shopwarePropertyGroupOption,
            $propertyGroupId,
            $option,
            $language,
            $shopwareLanguage
        );
    }

    /**
     * @param Shopware6Channel       $channel
     * @param AttributeId            $attributeId
     * @param AggregateId            $optionId
     * @param string                 $propertyGroupId
     * @param Shopware6Language|null $shopware6Language
     *
     * @return Shopware6PropertyGroup|null
     */
    private function loadPropertyGroupOption(
        Shopware6Channel $channel,
        AttributeId $attributeId,
        AggregateId $optionId,
        string $propertyGroupId,
        ?Shopware6Language $shopware6Language = null
    ): ?Shopware6PropertyGroupOption {
        $shopwareId = $this->propertyGroupOptionsRepository->load($channel->getId(), $attributeId, $optionId);

        if ($shopwareId) {
            try {
                return $this->propertyGroupOptionClient->get(
                    $channel,
                    $propertyGroupId,
                    $shopwareId,
                    $shopware6Language
                );
            } catch (ClientException $exception) {
            }
        }

        return null;
    }
}
