<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Domain\Command\Import;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class ImportTemplateCommand implements DomainCommandInterface
{
    private ImportId $importId;
    private TemplateId $id;
    private string $name;
    private Position $position;
    private Size $size;
    private string $type;
    private TemplateElementPropertyInterface $property;

    public function __construct(
        ImportId $importId,
        TemplateId $id,
        string $name,
        string $type,
        Position $position,
        Size $size,
        TemplateElementPropertyInterface $property
    ) {
        $this->importId = $importId;
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->position = $position;
        $this->size = $size;
        $this->property = $property;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getId(): TemplateId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function getSize(): Size
    {
        return $this->size;
    }

    public function getProperty(): TemplateElementPropertyInterface
    {
        return $this->property;
    }
}
