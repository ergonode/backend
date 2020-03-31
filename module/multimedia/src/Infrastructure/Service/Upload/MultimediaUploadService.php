<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Service\Upload;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Symfony\Component\HttpFoundation\File\File;
use Ergonode\Multimedia\Domain\ValueObject\Hash;

/**
 */
class MultimediaUploadService
{
    /**
     * @var string
     */
    private string $targetDirectory;

    /**
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param MultimediaId $id
     * @param File         $file
     * @param Hash         $hash
     *
     * @return File
     */
    public function upload(MultimediaId $id, File $file, Hash $hash): File
    {
        $fileName = sprintf('%s.%s', $hash->getValue(), $file->getExtension());

        return $file->move($this->targetDirectory, $fileName);
    }
}
