<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Entity\Source;

use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use JMS\Serializer\Annotation as JMS;

abstract class AbstractSource
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SourceId")
     */
    protected SourceId $id;

    /**
     * @JMS\Type("string")
     */
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
