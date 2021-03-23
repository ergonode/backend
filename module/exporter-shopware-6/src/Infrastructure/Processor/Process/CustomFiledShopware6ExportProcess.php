<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\CustomFieldRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Builder\CustomFieldBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6CustomFieldClient;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6CustomFieldSetClient;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterException;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomFieldSet;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldSetConfig;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomField;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldSet;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use GuzzleHttp\Exception\ClientException;
use Webmozart\Assert\Assert;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;

class CustomFiledShopware6ExportProcess
{
    private const CUSTOM_FIELD_SET_NAME = 'ergonode';

    private CustomFieldRepositoryInterface $customFieldRepository;

    private Shopware6CustomFieldClient $customFieldClient;

    private CustomFieldBuilder $builder;

    private Shopware6CustomFieldSetClient $customFieldSetClient;

    private ExportRepositoryInterface  $exportRepository;

    public function __construct(
        CustomFieldRepositoryInterface $customFieldRepository,
        Shopware6CustomFieldClient $customFieldClient,
        CustomFieldBuilder $builder,
        Shopware6CustomFieldSetClient $customFieldSetClient,
        ExportRepositoryInterface $exportRepository
    ) {
        $this->customFieldRepository = $customFieldRepository;
        $this->customFieldClient = $customFieldClient;
        $this->builder = $builder;
        $this->customFieldSetClient = $customFieldSetClient;
        $this->exportRepository = $exportRepository;
    }

    /**
     * @throws \Exception
     */
    public function process(
        ExportLineId $lineId,
        Export $export,
        Shopware6Channel $channel,
        AbstractAttribute $attribute
    ): void {
        $customField = $this->loadCustomField($channel, $attribute);
        try {
            if ($customField) {
                $this->updateCustomField($channel, $export, $customField, $attribute);
            } else {
                $customField = new Shopware6CustomField();
                $this->builder->build($channel, $export, $customField, $attribute);
                if ($customField->getCustomFieldSetId() === null) {
                    $customFieldSet = $this->loadCustomFieldSet($channel, $attribute);
                    $customField->setCustomFieldSetId($customFieldSet->getId());
                }
                $this->customFieldClient->insert($channel, $customField, $attribute);
            }
        } catch (Shopware6ExporterException $exception) {
            $this->exportRepository->addError($export->getId(), $exception->getMessage(), $exception->getParameters());
        }
        $this->exportRepository->processLine($lineId);
    }

    private function updateCustomField(
        Shopware6Channel $channel,
        Export $export,
        Shopware6CustomField $customField,
        AbstractAttribute $attribute,
        ?Language $language = null,
        ?Shopware6Language $shopwareLanguage = null
    ): void {
        $this->builder->build($channel, $export, $customField, $attribute, $language);

        if ($customField->isModified()) {
            $this->customFieldClient->update($channel, $customField, $shopwareLanguage);
        }
    }

    private function loadCustomField(
        Shopware6Channel $channel,
        AbstractAttribute $attribute,
        ?Shopware6Language $shopware6Language = null
    ): ?Shopware6CustomField {
        $shopwareId = $this->customFieldRepository->load($channel->getId(), $attribute->getId());
        if ($shopwareId) {
            try {
                return $this->customFieldClient->get($channel, $shopwareId, $shopware6Language);
            } catch (ClientException $exception) {
            }
        }

        return $this->customFieldClient->find($channel, $attribute, $shopware6Language);
    }

    private function loadCustomFieldSet(
        Shopware6Channel $channel,
        AbstractAttribute $attribute
    ): AbstractShopware6CustomFieldSet {
        $customFieldSet = $this->customFieldSetClient->findByCode($channel, self::CUSTOM_FIELD_SET_NAME);
        if ($customFieldSet) {
            return $customFieldSet;
        }
        $label = [
            str_replace('_', '-', $channel->getDefaultLanguage()->getCode()) => self::CUSTOM_FIELD_SET_NAME,
        ];

        $config = new Shopware6CustomFieldSetConfig(
            true,
            $label
        );

        $customFieldSet = new Shopware6CustomFieldSet(
            null,
            self::CUSTOM_FIELD_SET_NAME,
            $config,
            [
                [
                    'entityName' => 'product',
                ],
            ]
        );
        $newCustomFieldSet = $this->customFieldSetClient->insert($channel, $customFieldSet);
        Assert::notNull($newCustomFieldSet);

        return $newCustomFieldSet;
    }
}
