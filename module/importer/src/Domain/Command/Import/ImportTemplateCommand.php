<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportTemplateCommand implements ImporterCommandInterface
{
    private ImportLineId $id;

    private ImportId $importId;

    private string $code;

    private ?string $name;

    private array $elements;

    public function __construct(
        ImportLineId $id,
        ImportId $importId,
        string $code,
        array $elements = [],
        ?string $name = null
    ) {

        $this->id = $id;
        $this->importId = $importId;
        $this->name = $name ?? $code;
        $this->code = $code;
        $this->elements = $elements;

        if (empty($name)) {
            @trigger_error('property $name will be required from version 2.0.', \E_USER_DEPRECATED);
        }
    }

    public function getId(): ImportLineId
    {
        return $this->id;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getElements(): array
    {
        return $this->elements;
    }
}
