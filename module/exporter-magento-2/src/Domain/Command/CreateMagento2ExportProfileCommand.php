<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class CreateMagento2ExportProfileCommand implements DomainCommandInterface
{
    /**
     * @var  ExportProfileId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId")
     */
    protected ExportProfileId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $filename;

    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $defaultLanguage;

    /**
     * @param ExportProfileId $id
     * @param string          $name
     * @param string          $filename
     * @param Language        $defaultLanguage
     */
    public function __construct(ExportProfileId $id, string $name, string $filename, Language $defaultLanguage)
    {
        $this->id = $id;
        $this->name = $name;
        $this->filename = $filename;
        $this->defaultLanguage = $defaultLanguage;
    }


    /**
     * @return ExportProfileId
     */
    public function getId(): ExportProfileId
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
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }
}
