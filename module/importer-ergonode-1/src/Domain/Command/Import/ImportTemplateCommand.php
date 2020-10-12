<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\ImporterErgonode\Domain\Command\Import;

use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

/**
 */
final class ImportTemplateCommand implements DomainCommandInterface
{
    /**
     * @var ImportId
     */
    private ImportId $importId;

    /**
     * @var TemplateId
     */
    private TemplateId $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var Position
     */
    private Position $position;

    /**
     * @var Size
     */
    private Size $size;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var TemplateElementPropertyInterface
     */
    private TemplateElementPropertyInterface $property;

    /**
     * @param ImportId                         $importId
     * @param TemplateId                       $id
     * @param string                           $name
     * @param string                           $type
     * @param Position                         $position
     * @param Size                             $size
     * @param TemplateElementPropertyInterface $property
     */
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

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return TemplateId
     */
    public function getId(): TemplateId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position
    {
        return $this->position;
    }

    /**
     * @return Size
     */
    public function getSize(): Size
    {
        return $this->size;
    }

    /**
     * @return TemplateElementPropertyInterface
     */
    public function getProperty(): TemplateElementPropertyInterface
    {
        return $this->property;
    }
}
