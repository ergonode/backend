<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Product\Resolver;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Webmozart\Assert\Assert;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

class ImportProductSelectAttributeStrategy implements ImportProductAttributeStrategyInterface
{
    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $optionQuery;

    /**
     * @param OptionQueryInterface $optionQuery
     */
    public function __construct(OptionQueryInterface $optionQuery)
    {
        $this->optionQuery = $optionQuery;
    }

    /**
     * @param AttributeType $type
     *
     * @return bool
     */
    public function supported(AttributeType $type): bool
    {
        return SelectAttribute::TYPE === $type->getValue();
    }

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $value
     *
     * @return ValueInterface
     */
    public function build(AttributeId $id, AttributeCode $code, TranslatableString $value): ValueInterface
    {
        $result = [];
        foreach ($value->getTranslations() as $language => $version) {
            $key = new OptionKey($version);
            $optionId = $this->optionQuery->findIdByAttributeIdAndCode($id, $key);

            Assert::notNull(
                $optionId,
                sprintf('Can\'t find id for %s option in %s attribute', $key->getValue(), $code->getValue())
            );

            $result[$language] = $optionId;
        }

        return new TranslatableStringValue(new TranslatableString($result));
    }
}
