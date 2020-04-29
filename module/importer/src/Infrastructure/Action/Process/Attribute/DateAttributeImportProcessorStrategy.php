<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Infrastructure\Action\Process\AttributeImportProcessorStrategyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Transformer\Domain\Model\Record;
use Webmozart\Assert\Assert;

/**
 */
class DateAttributeImportProcessorStrategy implements AttributeImportProcessorStrategyInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return $type === DateAttribute::TYPE;
    }

    /**
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     *
     * @param bool               $multilingual
     * @param Record             $record
     * @param DateAttribute|null $attribute
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
        Assert::nullOrIsInstanceOf($attribute, DateAttribute::TYPE);

        $format = new DateFormat(DateFormat::YYYY_MM_DD);
        if ($record->has('format')) {
            $format = new DateFormat((string) $record->get('format'));
        }

        if (null === $attribute) {
            $attribute = new DateAttribute(
                AttributeId::generate(),
                $code,
                $label,
                $hint,
                $placeholder,
                $multilingual,
                $format,
            );
        } else {
            $attribute->changeFormat($format);
        }

        return $attribute;
    }
}
