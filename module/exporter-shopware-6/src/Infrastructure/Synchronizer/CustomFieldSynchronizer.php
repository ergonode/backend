<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6CustomFieldQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CustomFieldRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6CustomFieldClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomField;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class CustomFieldSynchronizer implements SynchronizerInterface
{
    /**
     * @var Shopware6CustomFieldClient
     */
    private Shopware6CustomFieldClient $client;

    /**
     * @var Shopware6CustomFieldQueryInterface
     */
    private Shopware6CustomFieldQueryInterface $customFieldQuery;

    /**
     * @var Shopware6CustomFieldRepositoryInterface
     */
    private Shopware6CustomFieldRepositoryInterface $customFieldRepository;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var AttributeTranslationInheritanceCalculator
     */
    private AttributeTranslationInheritanceCalculator $calculator;

    /**
     * @param Shopware6CustomFieldClient                $client
     * @param Shopware6CustomFieldQueryInterface        $customFieldQuery
     * @param Shopware6CustomFieldRepositoryInterface   $customFieldRepository
     * @param AttributeRepositoryInterface              $attributeRepository
     * @param AttributeTranslationInheritanceCalculator $calculator
     */
    public function __construct(
        Shopware6CustomFieldClient $client,
        Shopware6CustomFieldQueryInterface $customFieldQuery,
        Shopware6CustomFieldRepositoryInterface $customFieldRepository,
        AttributeRepositoryInterface $attributeRepository,
        AttributeTranslationInheritanceCalculator $calculator
    ) {
        $this->client = $client;
        $this->customFieldQuery = $customFieldQuery;
        $this->customFieldRepository = $customFieldRepository;
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
        $this->synchronizeCustomField($profile);
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     */
    private function synchronizeCustomField(Shopware6ExportApiProfile $profile): void
    {
        $attributes = $profile->getCustomField();
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

        $isset = $this->customFieldRepository->exists($profile->getId(), $attribute->getId());
        if ($isset) {
            return;
        }

        $code = $attribute->getCode()->getValue();

        $customField = new Shopware6CustomField(
            null,
            $code,
            [
                [
                    'entityName' => 'product',
                ],
            ]
        );

        $this->client->insert($profile, $customField);

        $new = $this->client->findByCode($profile, $code);

        $this->customFieldRepository->save($profile->getId(), $attributeId, $new->getId());
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     */
    private function synchronizeShopware(Shopware6ExportApiProfile $profile): void
    {
        $start = new \DateTimeImmutable();
        $customFieldList = $this->client->load($profile);

        foreach ($customFieldList as $customField) {
            $attributeId = $this->customFieldQuery->loadByShopwareId(
                $profile->getId(),
                $customField->getId()
            );
            if ($attributeId) {
                $this->customFieldRepository->save($profile->getId(), $attributeId, $customField->getId());
            }
        }
        $this->customFieldQuery->clearBefore($profile->getId(), $start);
    }
}
