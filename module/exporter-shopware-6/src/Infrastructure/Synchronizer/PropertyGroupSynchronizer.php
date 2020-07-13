<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6PropertyGroupQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class PropertyGroupSynchronizer implements SynchronizerInterface
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
     * @var AttributeTranslationInheritanceCalculator
     */
    private AttributeTranslationInheritanceCalculator $calculator;

    /**
     * @param Shopware6PropertyGroupClient              $client
     * @param Shopware6PropertyGroupQueryInterface      $propertyGroupQuery
     * @param Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository
     * @param AttributeRepositoryInterface              $attributeRepository
     * @param AttributeTranslationInheritanceCalculator $calculator
     */
    public function __construct(
        Shopware6PropertyGroupClient $client,
        Shopware6PropertyGroupQueryInterface $propertyGroupQuery,
        Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository,
        AttributeRepositoryInterface $attributeRepository,
        AttributeTranslationInheritanceCalculator $calculator
    ) {
        $this->client = $client;
        $this->propertyGroupQuery = $propertyGroupQuery;
        $this->propertyGroupRepository = $propertyGroupRepository;
        $this->attributeRepository = $attributeRepository;
        $this->calculator = $calculator;
    }

    /**
     * @param ExportId                  $id
     * @param Shopware6ExportApiProfile $profile
     */
    public function synchronize(ExportId $id, Shopware6ExportApiProfile $profile): void
    {
        $this->synchronizeShopware($profile);
        $this->synchronizeProperty($profile);
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     */
    private function synchronizeProperty(Shopware6ExportApiProfile $profile): void
    {
        $attributes = $profile->getPropertyGroup();
        foreach ($attributes as $attributeId) {
            $this->checkExistOrCreate($profile, $attributeId);
        }
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param AttributeId               $attributeId
     */
    private function checkExistOrCreate(Shopware6ExportApiProfile $profile, AttributeId $attributeId): void
    {
        $attribute = $this->attributeRepository->load($attributeId);

        $isset = $this->propertyGroupRepository->exists($profile->getId(), $attribute->getId());
        if ($isset) {
            //todo mayby update
            return;
        }

        $name = $attribute->getLabel()->get($profile->getDefaultLanguage());
        $name = $name ? $name : $attribute->getCode()->getValue();

        $propertyGroup = new Shopware6PropertyGroup(
            null,
            $name
        );

        $this->client->insert($profile, $propertyGroup);

        $new = $this->client->findByName($profile, $name);
        $this->propertyGroupRepository->save($profile->getId(), $attributeId, $new->getId());
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     */
    private function synchronizeShopware(Shopware6ExportApiProfile $profile): void
    {
        $start = new \DateTimeImmutable();
        $propertyGroupList = $this->client->load($profile);
        foreach ($propertyGroupList as $property) {
            $attributeId = $this->propertyGroupQuery->loadByShopwareId(
                $profile->getId(),
                $property->getId()
            );
            if ($attributeId) {
                $this->propertyGroupRepository->save($profile->getId(), $attributeId, $property->getId());
            }
        }
        $this->propertyGroupQuery->clearBefore($profile->getId(), $start);
    }
}
