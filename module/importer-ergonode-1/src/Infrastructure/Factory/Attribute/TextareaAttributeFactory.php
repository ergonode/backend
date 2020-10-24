<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Factory\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ImporterErgonode\Infrastructure\Model\AttributeParametersModel;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class TextareaAttributeFactory implements AttributeFactoryInterface
{
    public function supports(string $type): bool
    {
        return TextareaAttribute::TYPE === $type;
    }

    /**
     * @throws \Exception
     */
    public function create(
        AttributeId $id,
        AttributeCode $code,
        AttributeScope $scope,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeParametersModel $parameters
    ): AbstractAttribute {
        return new TextareaAttribute(
            $id,
            $code,
            $label,
            $hint,
            $placeholder,
            $scope,
            (bool) $parameters->get(TextareaAttribute::RICH_EDIT)
        );
    }
}
