<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Domain\Command;

use Ergonode\Importer\Domain\Command\UpdateSourceCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use JMS\Serializer\Annotation as JMS;

class UpdateErgonodeZipSourceCommand implements UpdateSourceCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SourceId")
     */
    private SourceId $id;

    /**
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @JMS\Type("array<string, bool>")
     */
    private array $import;

    public function __construct(
        SourceId $id,
        string $name,
        array $import = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->import = $import;
    }

    public function getId(): SourceId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImport(): array
    {
        return $this->import;
    }
}
