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
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

/**
 */
class UnitAttributeImportProcessorStrategy implements AttributeImportProcessorStrategyInterface
{
    /**
     * @var UnitQueryInterface
     */
    private UnitQueryInterface $query;

    /**
     * @param UnitQueryInterface $query
     */
    public function __construct(UnitQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return $type === UnitAttribute::TYPE;
    }

    /**
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     *
     * @param bool               $multilingual
     * @param Record             $record
     * @param UnitAttribute|null $attribute
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

        $unitsIds = $this->query->getAllUnitIds();
        $unitId = new UnitId(reset($unitsIds));
        if ($record->has('unit')) {
            $unitId = $this->query->findIdByCode($record->get('currency'));
            Assert::notNull($unitId);
        }

        if (null === $attribute) {
            $attribute = new UnitAttribute(
                AttributeId::generate(),
                $code,
                $label,
                $hint,
                $placeholder,
                $multilingual,
                $unitId,
            );
        } else {
            $attribute->changeUnit($unitId);
        }

        return $attribute;
    }
}
