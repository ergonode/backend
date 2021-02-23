<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Factory\Attribute;

use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Importer\Domain\Command\Attribute\ImportTextAttributeCommand;
use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportTextImportAttributeCommandFactory implements ImportAttributeCommandFactoryInterface
{
    public function supports(string $type): bool
    {
        return TextAttribute::TYPE === $type;
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
        return new ImportTextAttributeCommand(
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
