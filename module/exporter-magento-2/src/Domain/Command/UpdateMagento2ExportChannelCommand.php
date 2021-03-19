<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterMagento2\Domain\Command;

use Ergonode\Channel\Domain\Command\ChannelCommandInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class UpdateMagento2ExportChannelCommand implements ChannelCommandInterface
{
    protected ChannelId $id;

    protected string $name;

    private string $filename;

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
