<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Transformer\Domain\Model\Record;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Command\Option\CreateOptionCommand;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Attribute\Domain\Command\Option\UpdateOptionCommand;

/**
 */
class OptionImportAction implements ImportActionInterface
{
    public const TYPE = 'OPTION';

    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $optionQuery;

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @param AttributeQueryInterface $attributeQuery
     * @param OptionQueryInterface    $optionQuery
     * @param MessageBusInterface     $messageBus
     */
    public function __construct(
        AttributeQueryInterface $attributeQuery,
        OptionQueryInterface $optionQuery,
        MessageBusInterface $messageBus
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->optionQuery = $optionQuery;
        $this->messageBus = $messageBus;
    }

    /**
     * @param ImportId $importId
     * @param Record   $record
     *
     * @throws \Exception
     */
    public function action(ImportId $importId, Record $record): void
    {
        $attributeCode = $record->get('attribute_code') ?
            new AttributeCode($record->get('attribute_code')->getValue()) :
            null;
        $optionCode = $record->get('option_code') ? new OptionKey($record->get('code')->getValue()) : null;

        Assert::notNull($attributeCode, 'Attribute import required code field not exists');
        Assert::notNull($optionCode, 'Attribute import required option field not exists');


        $attributeModel = $this->attributeQuery->findAttributeByCode($attributeCode);
        Assert::notNull($attributeModel);
        $attributeId = $attributeModel->getId();
        $optionId = $this->optionQuery->findIdByAttributeIdAndCode($attributeId, $optionCode);

        $options = [];
        foreach ($record->getValues() as $key => $value) {
            $options[$key] = new StringOption($value->getValue());
        }

        $label = new TranslatableString($options);

        if (!$optionId) {
            $command = new CreateOptionCommand(
                $attributeId,
                $optionCode,
                $label
            );
        } else {
            $command = new UpdateOptionCommand(
                $optionId,
                $attributeId,
                $optionCode,
                $label
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
