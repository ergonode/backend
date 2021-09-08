<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Product\Strategy;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Importer\Infrastructure\Exception\ImportMissingOptionException;

class ImportProductSelectAttributeStrategy implements ImportProductAttributeStrategyInterface
{
    private OptionQueryInterface $optionQuery;

    public function __construct(OptionQueryInterface $optionQuery)
    {
        $this->optionQuery = $optionQuery;
    }

    public function supported(AttributeType $type): bool
    {
        return SelectAttribute::TYPE === $type->getValue();
    }

    public function build(AttributeId $id, AttributeCode $code, TranslatableString $value): ValueInterface
    {
        $result = [];
        foreach ($value->getTranslations() as $language => $version) {
            if (!$version) {
                continue;
            }

            $key = new OptionKey($version);
            $optionId = $this->optionQuery->findIdByAttributeIdAndCode($id, $key);

            if (null === $optionId) {
                throw new ImportMissingOptionException($key, $code);
            }

            $result[$language] = $optionId;
        }

        return new TranslatableStringValue(new TranslatableString($result));
    }
}
