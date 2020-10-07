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
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CustomFieldRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Builder\Shopware6CustomFieldBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6CustomFieldClient;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6CustomFieldSetClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomField;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6CustomFieldSet;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use GuzzleHttp\Exception\ClientException;
use Webmozart\Assert\Assert;

/**
 */
class CustomFiledShopware6ExportProcess
{
    /**
     * @var Shopware6CustomFieldRepositoryInterface
     */
    private Shopware6CustomFieldRepositoryInterface $customFieldRepository;

    /**
     * @var Shopware6CustomFieldClient
     */
    private Shopware6CustomFieldClient $customFieldClient;

    /**
     * @var Shopware6CustomFieldBuilder
     */
    private Shopware6CustomFieldBuilder $builder;

    /**
     * @var Shopware6CustomFieldSetClient
     */
    private Shopware6CustomFieldSetClient $customFieldSetClient;

    /**
     * @param Shopware6CustomFieldRepositoryInterface $customFieldRepository
     * @param Shopware6CustomFieldClient              $customFieldClient
     * @param Shopware6CustomFieldBuilder             $builder
     * @param Shopware6CustomFieldSetClient           $customFieldSetClient
     */
    public function __construct(
        Shopware6CustomFieldRepositoryInterface $customFieldRepository,
        Shopware6CustomFieldClient $customFieldClient,
        Shopware6CustomFieldBuilder $builder,
        Shopware6CustomFieldSetClient $customFieldSetClient
    ) {
        $this->customFieldRepository = $customFieldRepository;
        $this->customFieldClient = $customFieldClient;
        $this->builder = $builder;
        $this->customFieldSetClient = $customFieldSetClient;
    }

    /**
     * @param ExportId          $id
     * @param Shopware6Channel  $channel
     * @param AbstractAttribute $attribute
     */
    public function process(ExportId $id, Shopware6Channel $channel, AbstractAttribute $attribute): void
    {
        $customField = $this->loadCustomField($channel, $attribute);

        if ($customField) {
            $this->updateCustomField($channel, $customField, $attribute);
        } else {
            $customField = new Shopware6CustomField();
            $this->builder->build($channel, $customField, $attribute);
            if ($customField->getCustomFieldSetId() === null) {
                $customFieldSet = $this->loadCustomFieldSet($channel, $attribute);
                $customField->setCustomFieldSetId($customFieldSet->getId());
            }
            $this->customFieldClient->insert($channel, $customField, $attribute);
        }
    }

    /**
     * @param Shopware6Channel       $channel
     * @param Shopware6CustomField   $customField
     * @param AbstractAttribute      $attribute
     * @param Language|null          $language
     * @param Shopware6Language|null $shopwareLanguage
     */
    private function updateCustomField(
        Shopware6Channel $channel,
        Shopware6CustomField $customField,
        AbstractAttribute $attribute,
        ?Language $language = null,
        ?Shopware6Language $shopwareLanguage = null
    ): void {
        $this->builder->build($channel, $customField, $attribute, $language);

        if ($customField->isModified()) {
            $this->customFieldClient->update($channel, $customField, $shopwareLanguage);
        }
    }

    /**
     * @param Shopware6Channel       $channel
     * @param AbstractAttribute      $attribute
     * @param Shopware6Language|null $shopware6Language
     *
     * @return Shopware6CustomField|null
     */
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

    /**
     * @param Shopware6Channel  $channel
     * @param AbstractAttribute $attribute
     *
     * @return Shopware6CustomFieldSet
     */
    private function loadCustomFieldSet(
        Shopware6Channel $channel,
        AbstractAttribute $attribute
    ): Shopware6CustomFieldSet {
        $customFieldSet = $this->customFieldSetClient->findByCode($channel, 'ergonode');
        if ($customFieldSet) {
            return $customFieldSet;
        }

        $customFieldSet = new Shopware6CustomFieldSet(
            null,
            'ergonode',
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
