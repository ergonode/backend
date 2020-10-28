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
use Webmozart\Assert\Assert;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;

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
                $key = new OptionKey($item);
                $optionId = $this->optionQuery->findIdByAttributeIdAndCode($id, $key);

                Assert::notNull(
                    $optionId,
                    sprintf('Can\'t find id for %s option in %s attribute', $key->getValue(), $code->getValue())
                );
                $options[] = $optionId;
            }
            $result[$language] = implode(',', $options);
        }

        return new TranslatableStringValue(new TranslatableString($result));
    }
}
