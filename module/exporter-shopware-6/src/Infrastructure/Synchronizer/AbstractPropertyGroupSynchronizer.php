<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6PropertyGroupQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Webmozart\Assert\Assert;

/**
 */
abstract class AbstractPropertyGroupSynchronizer implements SynchronizerInterface
{
    /**
     * @var Shopware6PropertyGroupClient
     */
    private Shopware6PropertyGroupClient $client;

    /**
     * @var Shopware6PropertyGroupQueryInterface
     */
    private Shopware6PropertyGroupQueryInterface $propertyGroupQuery;

    /**
     * @var Shopware6PropertyGroupRepositoryInterface
     */
    private Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @param Shopware6PropertyGroupClient              $client
     * @param Shopware6PropertyGroupQueryInterface      $propertyGroupQuery
     * @param Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository
     * @param AttributeRepositoryInterface              $attributeRepository
     */
    public function __construct(
        Shopware6PropertyGroupClient $client,
        Shopware6PropertyGroupQueryInterface $propertyGroupQuery,
        Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository,
        AttributeRepositoryInterface $attributeRepository
    ) {
        $this->client = $client;
        $this->propertyGroupQuery = $propertyGroupQuery;
        $this->propertyGroupRepository = $propertyGroupRepository;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @param ExportId         $id
     * @param Shopware6Channel $channel
     */
    public function synchronize(ExportId $id, Shopware6Channel $channel): void
    {
        $this->synchronizeShopware($channel);
        $this->synchronizeProperty($channel);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isSupported(string $type): bool
    {
        return $this->getType() === $type;
    }

    /**
     * @param Shopware6Channel $channel
     */
    private function synchronizeProperty(Shopware6Channel $channel): void
    {
        $attributes = $channel->getPropertyGroup();
        foreach ($attributes as $attributeId) {
            $this->checkExistOrCreate($channel, $attributeId);
        }
    }

    /**
     * @param Shopware6Channel $channel
     * @param AttributeId      $attributeId
     */
    private function checkExistOrCreate(Shopware6Channel $channel, AttributeId $attributeId): void
    {
        $attribute = $this->attributeRepository->load($attributeId);
        Assert::notNull($attribute);
        if ($this->isSupported($attribute->getType())) {
            $isset = $this->propertyGroupRepository->exists($channel->getId(), $attribute->getId());
            if ($isset) {
                return;
            }
            $this->createShopwarePropertyGroup($channel, $attribute);
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

        $new = $this->client->createPropertyGroupResource($channel, $propertyGroup);

        $this->propertyGroupRepository->save(
            $channel->getId(),
            $attribute->getId(),
            $new->getId(),
            $attribute->getType()
        );
    }

    /**
     * @param Shopware6Channel $channel
     */
    private function synchronizeShopware(Shopware6Channel $channel): void
    {
        $start = new \DateTimeImmutable();
        $propertyGroupList = $this->client->load($channel);
        foreach ($propertyGroupList as $property) {
            $attributeId = $this->propertyGroupQuery->loadByShopwareId(
                $channel->getId(),
                $property->getId()
            );
            if ($attributeId) {
                $attribute = $this->attributeRepository->load($attributeId);
                Assert::notNull($attribute);
                $this->propertyGroupRepository->save(
                    $channel->getId(),
                    $attributeId,
                    $property->getId(),
                    $attribute->getType()
                );
            }
        }
        $this->propertyGroupQuery->cleanData($channel->getId(), $start, $this->getType());
    }
}
