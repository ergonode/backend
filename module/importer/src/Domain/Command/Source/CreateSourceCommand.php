<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command\Source;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateSourceCommand implements DomainCommandInterface
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
    private string $sourceType;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var array
     */
    private array $configuration;

    /**
     * @param SourceId $id
     * @param string   $sourceType
     * @param string   $name
     * @param array    $configuration
     */
    public function __construct(SourceId $id, string $sourceType, string $name, array $configuration = [])
    {
        $this->id = $id;
        $this->sourceType = $sourceType;
        $this->name = $name;
        $this->configuration = $configuration;
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
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @return string
     */
    public function getSourceType(): string
    {
        return $this->sourceType;
    }
}
