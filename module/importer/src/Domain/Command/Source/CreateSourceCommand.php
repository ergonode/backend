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
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $filename;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $sourceType;

    /**
     * @param string $filename
     * @param string $sourceType
     *
     * @throws \Exception
     */
    public function __construct(string $filename, string $sourceType)
    {
        $this->id = SourceId::generate();
        $this->filename = $filename;
        $this->sourceType = $sourceType;
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
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getSourceType(): string
    {
        return $this->sourceType;
    }
}
