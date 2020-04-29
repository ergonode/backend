<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Infrastructure\Action\Process\AttributeImportProcessorStrategyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Transformer\Domain\Model\Record;
use Webmozart\Assert\Assert;

/**
 */
class TextareaAttributeImportProcessorStrategy implements AttributeImportProcessorStrategyInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return $type === TextareaAttribute::TYPE;
    }

    /**
     * @param AttributeCode          $code
     * @param TranslatableString     $label
     * @param TranslatableString     $hint
     * @param TranslatableString     $placeholder
     *
     * @param bool                   $multilingual
     * @param Record                 $record
     * @param AbstractAttribute|null $attribute
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

        Assert::nullOrIsInstanceOf($attribute, TextareaAttribute::TYPE);

        if (null === $attribute) {
            $attribute = new TextareaAttribute(
                AttributeId::generate(),
                $code,
                $label,
                $hint,
                $placeholder,
                $multilingual,
            );
        }

        return $attribute;
    }
}
