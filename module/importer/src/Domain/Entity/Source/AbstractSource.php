<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Entity\Source;

use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

/**
 */
abstract class AbstractSource
{
    /**
     * @var SourceId
     */
    protected SourceId $id;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @param SourceId $id
     * @param string   $name
     */
    public function __construct(SourceId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
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
     * @return string
     */
    abstract public function getType(): string;
}
