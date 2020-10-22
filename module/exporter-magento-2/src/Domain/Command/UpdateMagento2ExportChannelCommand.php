<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use JMS\Serializer\Annotation as JMS;

class UpdateMagento2ExportChannelCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ChannelId")
     */
    protected ChannelId $id;

    /**
     * @JMS\Type("string")
     */
    protected string $name;

    /**
     * @JMS\Type("string")
     */
    private string $filename;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $defaultLanguage;

    public function __construct(ChannelId $id, string $name, string $filename, Language $defaultLanguage)
    {
        $this->id = $id;
        $this->name = $name;
        $this->filename = $filename;
        $this->defaultLanguage = $defaultLanguage;
    }

    public function getId(): ChannelId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }
}
