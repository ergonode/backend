<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6LanguageRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Builder\Shopware6PropertyGroupBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use GuzzleHttp\Exception\ClientException;
use Webmozart\Assert\Assert;

class PropertyGroupShopware6ExportProcess
{
    private Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository;

    private Shopware6PropertyGroupClient $propertyGroupClient;

    private Shopware6PropertyGroupBuilder $builder;

    private Shopware6LanguageRepositoryInterface  $languageRepository;

    private PropertyGroupOptionsShopware6ExportProcess $propertyGroupOptionsProcess;

    public function __construct(
        Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository,
        Shopware6PropertyGroupClient $propertyGroupClient,
        Shopware6PropertyGroupBuilder $builder,
        Shopware6LanguageRepositoryInterface $languageRepository,
        PropertyGroupOptionsShopware6ExportProcess $propertyGroupOptionsProcess
    ) {
        $this->propertyGroupRepository = $propertyGroupRepository;
        $this->propertyGroupClient = $propertyGroupClient;
        $this->builder = $builder;
        $this->languageRepository = $languageRepository;
        $this->propertyGroupOptionsProcess = $propertyGroupOptionsProcess;
    }

    public function process(ExportId $id, Shopware6Channel $channel, AbstractAttribute $attribute): void
    {
        $propertyGroup = $this->loadPropertyGroup($channel, $attribute);
        if ($propertyGroup) {
            $this->updatePropertyGroup($channel, $propertyGroup, $attribute);
        } else {
            $propertyGroup = new Shopware6PropertyGroup();
            $this->builder->build($channel, $propertyGroup, $attribute);
            $this->propertyGroupClient->insert($channel, $propertyGroup, $attribute);
        }

        foreach ($channel->getLanguages() as $language) {
            if ($this->languageRepository->exists($channel->getId(), $language->getCode())) {
                $this->updatePropertyGroupWithLanguage($channel, $language, $attribute);
            }
        }
        $this->propertyGroupOptionsProcess->process($id, $channel, $attribute);
    }

    private function updatePropertyGroup(
        Shopware6Channel $channel,
        Shopware6PropertyGroup $propertyGroup,
        AbstractAttribute $attribute,
        ?Language $language = null,
        ?Shopware6Language $shopwareLanguage = null
    ): void {
        $this->builder->build($channel, $propertyGroup, $attribute, $language);
        if ($propertyGroup->isModified()) {
            $this->propertyGroupClient->update($channel, $propertyGroup, $shopwareLanguage);
        }
    }

    private function updatePropertyGroupWithLanguage(
        Shopware6Channel $channel,
        Language $language,
        AbstractAttribute $attribute
    ): void {
        $shopwareLanguage = $this->languageRepository->load($channel->getId(), $language->getCode());
        Assert::notNull($shopwareLanguage);

        $shopwarePropertyGroup = $this->loadPropertyGroup($channel, $attribute, $shopwareLanguage);
        Assert::notNull($shopwarePropertyGroup);

        $this->updatePropertyGroup($channel, $shopwarePropertyGroup, $attribute, $language, $shopwareLanguage);
    }

    private function loadPropertyGroup(
        Shopware6Channel $channel,
        AbstractAttribute $attribute,
        ?Shopware6Language $shopware6Language = null
    ): ?Shopware6PropertyGroup {
        $shopwareId = $this->propertyGroupRepository->load($channel->getId(), $attribute->getId());
        if ($shopwareId) {
            try {
                return $this->propertyGroupClient->get($channel, $shopwareId, $shopware6Language);
            } catch (ClientException $exception) {
            }
        }

        return null;
    }
}
