<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Symfony\Component\HttpFoundation\File\File;

/**
 */
class AddMultimediaCommand implements DomainCommandInterface
{
    /**
     * @var MultimediaId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var File
     */
    private $file;

    /**
     * @param string $name
     * @param File   $file
     *
     * @throws \Exception
     */
    public function __construct(string $name, File $file)
    {
        $this->id = MultimediaId::fromKey($name);
        $this->name = $name;
        $this->file = $file;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }
}
