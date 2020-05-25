<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class CreateFileExportProfileCommand implements DomainCommandInterface
{
    /**
     * @var  ExportProfileId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId")
     */
    protected ExportProfileId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $format;

    /**
     * @param ExportProfileId $id
     * @param string          $name
     * @param string          $format
     */
    public function __construct(ExportProfileId $id, string $name, string $format)
    {
        $this->id = $id;
        $this->name = $name;
        $this->format = $format;
    }


    /**
     * @return ExportProfileId
     */
    public function getId(): ExportProfileId
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
    public function getFormat(): string
    {
        return $this->format;
    }
}
