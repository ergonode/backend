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
     * @var array $configuration
     */
    protected array $configuration;

    /**
     * @param SourceId $id
     */
    public function __construct(SourceId $id)
    {
        $this->id = $id;
        $this->configuration = [];
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
    abstract public function getType(): string;

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}
