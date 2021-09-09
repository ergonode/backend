<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

class StopImportCommand implements ImporterCommandInterface
{
    private ImportId $id;

    private ?string $message;

    /**
     * @var string[];
     */
    private array $parameters;

    public function __construct(ImportId $id, ?string $message = null, array $parameters = [])
    {
        $this->id = $id;
        $this->message = $message;
        $this->parameters = $parameters;
    }

    public function getId(): ImportId
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return string[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
