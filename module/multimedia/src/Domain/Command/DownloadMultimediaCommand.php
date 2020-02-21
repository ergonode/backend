<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Symfony\Component\HttpFoundation\File\File;

/**
 */
class DownloadMultimediaCommand implements DomainCommandInterface
{
    /**
     * @var MultimediaId
     */
    private MultimediaId $id;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    private string $name;

    /**
     * @param MultimediaId $id
     * @param string       $url
     * @param string       $name
     */
    public function __construct(MultimediaId $id, string $url, string $name)
    {
        $this->id = $id;
        $this->url = $url;
        $this->name = $name;
    }

    /**
     * @return MultimediaId
     */
    public function getId(): MultimediaId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
