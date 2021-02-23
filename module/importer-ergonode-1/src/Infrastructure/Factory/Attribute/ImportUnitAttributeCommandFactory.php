<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Factory\Attribute;

use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Importer\Domain\Command\Attribute\ImportUnitAttributeCommand;
use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportUnitAttributeCommandFactory implements ImportAttributeCommandFactoryInterface
{
    public function supports(string $type): bool
    {
        return UnitAttribute::TYPE === $type;
    }

    public function create(
        ImportLineId $id,
        ImportId $importId,
        string $code,
        string $type,
        array $label,
        array $hint,
        array $placeholder,
        string $scope,
        array $parameters
    ): ImporterCommandInterface {
        return new ImportUnitAttributeCommand(
            $id,
            $importId,
            $code,
            $type,
            $label,
            $hint,
            $placeholder,
            $scope,
            $parameters
        );
    }
}
