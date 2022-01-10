<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Provider;

class MultimediaExtensionProvider
{
    /**
     * @return string[]
     */
    public function dictionary(): array
    {
        return [
            'doc',
            'docx',
            'gif',
            'jpeg',
            'jpg',
            'webp',
            'svg',
            'eps',
            'ods',
            'odt',
            'pdf',
            'bmp',
            'tif',
            'tiff',
            'txt',
            'csv',
            'xlsx',
            'xls',
            'png',
            'zip',
            'key',
            'ppt',
            'pptx',
        ];
    }

    /**
     * @return string[]
     */
    public function mimeDictionary(): array
    {
        return [
            'application/msword', //doc
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', //docx
            'image/gif',
            'image/jpeg',
            'image/webp',
            'image/svg+xml',
            'application/postscript', //eps
            'application/vnd.oasis.opendocument.spreadsheet', //ods
            'application/vnd.oasis.opendocument.text', //odt
            'application/pdf',
            'image/bmp',
            'image/tiff',
            'text/plain',
            'text/csv',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'image/png',
            'application/zip',
            'application/pkcs8', //key
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];
    }
}
