<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductSimple\Infrastructure\Action\Extension;

use Ergonode\Attribute\Domain\Command\AddAttributeOptionCommand;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableCollectionValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 */
class ProductAttributeExtension
{
    /**
     * @var AttributeQueryInterface
     */
    private $query;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param AttributeQueryInterface $query
     * @param MessageBusInterface     $messageBus
     */
    public function __construct(AttributeQueryInterface $query, MessageBusInterface $messageBus)
    {
        $this->query = $query;
        $this->messageBus = $messageBus;
    }

    /**
     * @param Record $record
     *
     * @param array  $data
     *
     * @return array
     *
     * @throws \Exception
     */
    public function extend(Record $record, array $data): array
    {
        if ($record->has('values')) {
            foreach ($record->getColumns('values') as $key => $value) {
                $attributeCode = new AttributeCode($key);
                if (null !== $value) {
                    $attributeId = AttributeId::fromKey($attributeCode);
                    $attributeType = $this->query->findAttributeType($attributeId);
                    if ($attributeType && in_array($attributeType->getValue(), [SelectAttribute::TYPE, MultiSelectAttribute::TYPE], true)) {
                        $value = $this->process($attributeId, $value);
                    }

                    if ($value) {
                        $data['attributes'][$attributeCode->getValue()] = $value;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @param AttributeId    $attributeId
     * @param ValueInterface $value
     *
     * @return ValueInterface|null
     */
    public function process(AttributeId $attributeId, ValueInterface $value): ?ValueInterface
    {
        if ($value instanceof TranslatableCollectionValue) {
            $collection = $value->getValue();
            foreach ($collection as $key => $translations) {
                if ('' !== $key) {
                    $key = new OptionKey((string) $key);
                    if (!$this->query->findAttributeOption($attributeId, $key)) {
                        $command = new AddAttributeOptionCommand($attributeId, $key, new MultilingualOption($translations));
                        $this->messageBus->dispatch($command);
                    }

                    return new StringValue($key->getValue());
                }
            }
        } elseif ($value instanceof StringValue) {
            $key = $value->getValue();
            if ('' !== $key) {
                $key = new OptionKey($value->getValue());
                if (!$this->query->findAttributeOption($attributeId, $key)) {
                    $command = new AddAttributeOptionCommand($attributeId, $key, new StringOption($value->getValue()));
                    $this->messageBus->dispatch($command);
                }

                return new StringValue($key->getValue());
            }
        } elseif ($value instanceof StringCollectionValue) {
            foreach ($value->getValue() as $string) {
                if ('' !== $string) {
                    $key = new OptionKey($string);
                    if (!$this->query->findAttributeOption($attributeId, $key)) {
                        $command = new AddAttributeOptionCommand($attributeId, $key, new StringOption($string));
                        $this->messageBus->dispatch($command);
                    }
                }
            }

            return $value;
        }

        return null;
    }
}
