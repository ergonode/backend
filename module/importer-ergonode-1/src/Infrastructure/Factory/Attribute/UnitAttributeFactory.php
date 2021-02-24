<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Factory\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ImporterErgonode1\Infrastructure\Model\AttributeParametersModel;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\Query\UnitQueryInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;

class UnitAttributeFactory implements AttributeFactoryInterface
{
    private UnitQueryInterface $unitQuery;

    public function __construct(UnitQueryInterface $unitQuery)
    {
        $this->unitQuery = $unitQuery;
    }

    public function supports(string $type): bool
    {
        return UnitAttribute::TYPE === $type;
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

        $unitId = $this->unitQuery->findIdByCode($parameters->get(UnitAttribute::UNIT));
        if(null === $unitId) {
            throw new ImportException('no unit');
        }

        return new UnitAttribute(
            $id,
            $code,
            $label,
            $hint,
            $placeholder,
            $scope,
            $unitId
        );
    }
}
