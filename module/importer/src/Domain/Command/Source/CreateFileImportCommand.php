<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command\Source;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Domain\Entity\ImportId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateFileImportCommand implements DomainCommandInterface
{
    /**
     * @var ImportId
     *
     * @JMS\Type("Ergonode\Importer\Domain\Entity\ImportId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $name;

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
     * @param string $name
     * @param string $filename
     * @param string $sourceType
     *
     * @throws \Exception
     */
    public function __construct(string $name, string $filename, string $sourceType)
    {
        $this->id = ImportId::generate();
        $this->name = $name;
        $this->filename = $filename;
        $this->sourceType = $sourceType;
    }

    /**
     * @return ImportId
     */
    public function getId(): ImportId
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
