<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Entity\Source;

use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

abstract class AbstractSource
{
    protected SourceId $id;

    protected string $name;

    public function __construct(SourceId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): SourceId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    abstract public function getType(): string;
}
