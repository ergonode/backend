<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Query\Shopware6CustomFieldQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CustomFieldRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6CustomFieldClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomField;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Webmozart\Assert\Assert;

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
     * @param ExportId         $id
     * @param Shopware6Channel $channel
     */
    public function synchronize(ExportId $id, Shopware6Channel $channel): void
    {
        $this->synchronizeShopware($channel);
        $this->synchronizeCustomField($channel);
    }

    /**
     * @param Shopware6Channel $channel
     */
    private function synchronizeCustomField(Shopware6Channel $channel): void
    {
        $attributes = $channel->getCustomField();
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

        $isset = $this->customFieldRepository->exists($channel->getId(), $attribute->getId());
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

        $this->client->insert($channel, $customField);

        $new = $this->client->findByCode($channel, $code);

        $this->customFieldRepository->save($channel->getId(), $attributeId, $new->getId());
    }

    /**
     * @param Shopware6Channel $channel
     */
    private function synchronizeShopware(Shopware6Channel $channel): void
    {
        $start = new \DateTimeImmutable();
        $customFieldList = $this->client->load($channel);

        foreach ($customFieldList as $customField) {
            $attributeId = $this->customFieldQuery->loadByShopwareId(
                $channel->getId(),
                $customField->getId()
            );
            if ($attributeId) {
                $this->customFieldRepository->save($channel->getId(), $attributeId, $customField->getId());
            }
        }
        $this->customFieldQuery->clearBefore($channel->getId(), $start);
    }
}
