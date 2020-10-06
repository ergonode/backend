<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UpdateErgonodeZipSourceCommand implements DomainCommandInterface
{
    /**
     * @var SourceId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SourceId")
     */
    private SourceId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var array
     *
     * @JMS\Type("array<string, bool>")
     */
    private array $import;

    /**
     * @param SourceId $id
     * @param string   $name
     * @param array    $import
     */
    public function __construct(
        SourceId $id,
        string $name,
        array $import = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->import = $import;
    }

    /**
     * @return SourceId
     */
    public function getId(): SourceId
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
     * @return array
     */
    public function getImport(): array
    {
        return $this->import;
    }
}
