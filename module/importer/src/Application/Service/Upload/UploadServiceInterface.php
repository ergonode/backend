<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Service\Upload;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface FileServiceInterface
 */
interface UploadServiceInterface
{
    /**
     * @param UploadedFile $file
     *
     * @return File
     */
    public function upload(UploadedFile $file): File;
}
