<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Factory\Attribute;

use Ergonode\Attribute\Domain\Entity\Attribute\FileAttribute;
use Ergonode\Importer\Domain\Command\Attribute\ImportDateAttributeCommand;
use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportFileAttributeCommandFactory implements ImportAttributeCommandFactoryInterface
{
    public function supports(string $type): bool
    {
        return FileAttribute::TYPE === $type;
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
        return new ImportDateAttributeCommand(
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
