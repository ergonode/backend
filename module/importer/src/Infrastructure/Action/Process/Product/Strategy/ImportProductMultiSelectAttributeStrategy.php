<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Product\Strategy;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Importer\Infrastructure\Exception\ImportMissingOptionException;

class ImportProductMultiSelectAttributeStrategy implements ImportProductAttributeStrategyInterface
{
    private OptionQueryInterface $optionQuery;

    public function __construct(OptionQueryInterface $optionQuery)
    {
        $this->optionQuery = $optionQuery;
    }

    public function supported(AttributeType $type): bool
    {
        return MultiSelectAttribute::TYPE === $type->getValue();
    }

    public function build(AttributeId $id, AttributeCode $code, TranslatableString $value): ValueInterface
    {
        $result = [];
        foreach ($value->getTranslations() as $language => $version) {
            $options = [];
            foreach (explode(',', $version) as $item) {
                if (!$item) {
                    continue;
                }
                $key = new OptionKey($item);
                $optionId = $this->optionQuery->findIdByAttributeIdAndCode($id, $key);
                if (null === $optionId) {
                    throw new ImportMissingOptionException($key, $code);
                }
                $options[] = $optionId;
            }
            if (!$options) {
                continue;
            }
            $result[$language] = implode(',', $options);
        }

        return new StringCollectionValue($result);
    }
}
