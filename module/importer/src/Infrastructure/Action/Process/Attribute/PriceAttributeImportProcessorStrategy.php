<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Attribute;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Webmozart\Assert\Assert;
use Ergonode\Importer\Infrastructure\Action\Process\AttributeImportProcessorStrategyInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Money\Currency;

/**
 */
class PriceAttributeImportProcessorStrategy implements AttributeImportProcessorStrategyInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return $type === PriceAttribute::TYPE;
    }

    /**
     * @param AttributeCode       $code
     * @param TranslatableString  $label
     * @param TranslatableString  $hint
     * @param TranslatableString  $placeholder
     *
     * @param bool                $multilingual
     * @param Record              $record
     * @param PriceAttribute|null $attribute
     *
     * @return AbstractAttribute
     *
     * @throws \Exception
     */
    public function process(
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        bool $multilingual,
        Record $record,
        ?AbstractAttribute $attribute = null
    ): AbstractAttribute {
        Assert::nullOrIsInstanceOf($attribute, PriceAttribute::TYPE);

        $currency = new Currency('EUR');
        if ($record->has('currency')) {
            $currency = new Currency((string) $record->get('currency'));
        }

        if (null === $attribute) {
            $attribute = new PriceAttribute(
                AttributeId::generate(),
                $code,
                $label,
                $hint,
                $placeholder,
                $multilingual,
                $currency,
            );
        } else {
            $attribute->changeCurrency($currency);
        }

        return $attribute;
    }
}
