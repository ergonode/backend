<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Action;

use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\AttributeImage\Domain\ValueObject\ImageFormat;
use Ergonode\AttributeDate\Domain\Entity\DateAttribute;
use Ergonode\AttributeImage\Domain\Entity\ImageAttribute;
use Ergonode\AttributePrice\Domain\Entity\PriceAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Action\ImportActionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

/**
 */
class AttributeImportAction implements ImportActionInterface
{
    public const TYPE = 'ATTRIBUTE';

    /**
     * @var AttributeQueryInterface
     */
    private $attributeQuery;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param AttributeQueryInterface $attributeQuery
     * @param MessageBusInterface     $messageBus
     */
    public function __construct(
        AttributeQueryInterface $attributeQuery,
        MessageBusInterface $messageBus
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->messageBus = $messageBus;
    }

    /**
     * @param Record $record
     *
     * @throws \Exception
     */
    public function action(Record $record): void
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

        if (($attributeType->getValue() === PriceAttribute::TYPE) && $currency = $record->get('currency')) {
            $property['currency'] = $currency->getValue();
        }

        if (($attributeType->getValue() === DateAttribute::TYPE) && $format = $record->get('format')) {
            $property['format'] = $format->getValue();
        }

        if ($attributeType->getValue() === ImageAttribute::TYPE) {
            $property['formats'] = $record->has('format') ? [$record->get('format')->getValue()] : ImageFormat::AVAILABLE;
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

        $this->messageBus->dispatch($command);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
