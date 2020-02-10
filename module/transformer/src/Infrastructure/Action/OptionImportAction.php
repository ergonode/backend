<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class OptionImportAction implements ImportActionInterface
{
    public const TYPE = 'OPTION';

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @param Record $record
     *
     * @throws \Exception
     */
    public function action(Record $record): void
    {
        $attributeCode = new AttributeCode($record->get('attribute')->getValue());
        $attributeId = AttributeId::fromKey($attributeCode->getValue());
        $key = new OptionKey($record->get('key')->getValue());
        $option = $this->getOption($record->get('value'));

        $attribute = $this->attributeRepository->load($attributeId);
        if ($attribute instanceof AbstractOptionAttribute) {
            if ($attribute->hasOption($key)) {
                $attribute->changeOption($key, $option);
            } else {
                $attribute->addOption($key, $option);
            }
        }

        $this->attributeRepository->save($attribute);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param ValueInterface $value
     *
     * @return OptionInterface
     */
    private function getOption(ValueInterface $value): OptionInterface
    {
        if ($value instanceof TranslatableStringValue) {
            return new MultilingualOption($value->getValue());
        }

        return new StringOption($value->getValue());
    }
}
