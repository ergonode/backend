<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Command;

use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Ergonode\Multimedia\Domain\Factory\MultimediaIdFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 */
class UploadMultimediaCommand
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
     * @var UploadedFile
     */
    private $file;

    /**
     * @param string       $name
     * @param UploadedFile $file
     */
    public function __construct(string $name, UploadedFile $file)
    {
        $this->id = MultimediaIdFactory::createFromFile($file);
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
     * @return UploadedFile
     */
    public function getFile(): UploadedFile
    {
        return $this->file;
    }
}
