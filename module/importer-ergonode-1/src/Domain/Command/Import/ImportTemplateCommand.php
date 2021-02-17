<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Domain\Command\Import;

use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportTemplateCommand implements DomainCommandInterface
{
    private ImportLineId $id;
    private ImportId $importId;
    private string $name;
    private string $type;
    private int $x;
    private int $y;
    private int $width;
    private int $height;
    private string $property;

    public function __construct(
        ImportLineId $id,
        ImportId $importId,
        string $name,
        string $type,
        int $x,
        int $y,
        int $width,
        int $height,
        string $property
    ) {
        $this->id = $id;
        $this->importId = $importId;
        $this->name = $name;
        $this->type = $type;
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
        $this->property = $property;
    }

    public function getId(): ImportLineId
    {
        return $this->id;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getProperty(): string
    {
        return $this->property;
    }
}
