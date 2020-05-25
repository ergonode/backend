<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action\Process;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Transformer\Domain\Model\Record;

/**
 */
interface AttributeImportProcessorStrategyInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param AttributeCode          $code
     * @param TranslatableString     $label
     * @param TranslatableString     $hint
     * @param TranslatableString     $placeholder
     * @param bool                   $multilingual
     * @param Record                 $parameters
     * @param AbstractAttribute|null $attribute
     *
     * @return AbstractAttribute
     */
    public function process(
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        bool $multilingual,
        Record $parameters,
        ?AbstractAttribute $attribute = null
    ): AbstractAttribute;
}
