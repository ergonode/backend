<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Transformer\Domain\Model\Record;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Importer\Infrastructure\Action\Process\AttributeImportProcessorProvider;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;

/**
 */
class AttributeImportAction implements ImportActionInterface
{
    public const TYPE = 'ATTRIBUTE';

    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $query;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var AttributeImportProcessorProvider
     */
    private AttributeImportProcessorProvider $provider;

    /**
     * @param AttributeQueryInterface          $query
     * @param AttributeRepositoryInterface     $repository
     * @param AttributeImportProcessorProvider $provider
     */
    public function __construct(
        AttributeQueryInterface $query,
        AttributeRepositoryInterface $repository,
        AttributeImportProcessorProvider $provider
    ) {
        $this->query = $query;
        $this->repository = $repository;
        $this->provider = $provider;
    }

    /**
     * @param ImportId $importId
     * @param Record   $record
     *
     * @throws \Exception
     */
    public function action(ImportId $importId, Record $record): void
    {
        $attributeCode = $record->get('code') ? new AttributeCode($record->get('code')) : null;
        $attributeType = $record->get('type') ? new AttributeType($record->get('type')) : null;

        Assert::notNull($attributeCode, 'Attribute import required code field not exists');
        Assert::notNull($attributeType, 'Attribute import required type field not exists');

        $label = $record->getTranslation('label');

        $multilingual = false;
        if ($record->has('multilingual')) {
            $multilingual = (bool) $record->get('multilingual');
        }

        $attribute = null;

        $attributeModel = $this->query->findAttributeByCode($attributeCode);
        if ($attributeModel) {
            /** @var PriceAttribute $attribute */
            $attribute = $this->repository->load($attributeModel->getId());
        }

        $processor = $this->provider->provide($attributeType->getValue());
        $processor->process(
            $attributeCode,
            $label,
            new TranslatableString(),
            new TranslatableString(),
            $multilingual,
            $record,
            $attribute,
        );

        $this->repository->save($attribute);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
