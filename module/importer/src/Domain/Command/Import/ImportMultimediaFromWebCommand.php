<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class ImportMultimediaFromWebCommand implements ImporterCommandInterface
{
    private ImportLineId $id;

    private ImportId $importId;

    private string $url;

    private string $name;

    private ?TranslatableString $label;

    public function __construct(
        ImportLineId $id,
        ImportId $importId,
        string $url,
        string $name,
        ?TranslatableString $label = null
    ) {
        $this->id = $id;
        $this->importId = $importId;
        $this->url = $url;
        $this->name = $name;
        $this->label = $label;
    }

    public function getId(): ImportLineId
    {
        return $this->id;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): ?TranslatableString
    {
        return $this->label;
    }
}
