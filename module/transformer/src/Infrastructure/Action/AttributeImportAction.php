<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;

/**
 */
class AttributeImportAction implements ImportActionInterface
{
    public const TYPE = 'ATTRIBUTE';

    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param AttributeQueryInterface $attributeQuery
     * @param CommandBusInterface     $commandBus
     */
    public function __construct(AttributeQueryInterface $attributeQuery, CommandBusInterface $commandBus)
    {
        $this->attributeQuery = $attributeQuery;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ImportId $importId
     * @param Record   $record
     *
     * @throws \Exception
     */
    public function action(ImportId $importId, Record $record): void
    {
        $property = [];

        $attributeCode = $record->get('code')?new AttributeCode($record->get('code')->getValue()):null;
        $attributeType = $record->get('type')?new AttributeType($record->get('type')->getValue()):null;

        Assert::notNull($attributeCode, 'Attribute import required code field not exists');
        Assert::notNull($attributeType, 'Attribute import required type field not exists');

        /** @var TranslatableStringValue $label */
        $label = $record->get('label');
        $attributeModel = $this->attributeQuery->findAttributeByCode($attributeCode);

        if ($multilingual = $record->get('multilingual')) {
            $multilingual = (bool) $multilingual->getValue();
        } else {
            $multilingual = false;
        }

        if ($attributeType->getValue() === PriceAttribute::TYPE) {
            $property['currency'] = $record->has('currency') ? $record->get('currency')->getValue() : null;
        }

        if ($attributeType->getValue() === DateAttribute::TYPE) {
            $format = $record->has('format') ? $record->get('format')->getValue() : null;
            $property['format'] = $format->getValue();
        }

        if ($attributeType->getValue() === UnitAttribute::TYPE) {
            $format = $record->has('unit') ? $record->get('unit')->getValue() : null;
            $property['format'] = $format->getValue();
        }

        if (null === $attributeModel) {
            $command = new CreateAttributeCommand(
                $attributeType,
                $attributeCode,
                $label->getValue(),
                new TranslatableString(),
                new TranslatableString(),
                $multilingual,
                [],
                $property
            );
        } else {
            $command = new UpdateAttributeCommand(
                $attributeModel->getId(),
                $label->getValue(),
                new TranslatableString(),
                new TranslatableString(),
                [],
                $property
            );
        }

        $this->commandBus->dispatch($command, true);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
