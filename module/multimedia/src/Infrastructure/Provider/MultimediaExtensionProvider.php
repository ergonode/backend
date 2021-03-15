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
     * @return array
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
            'svg',
            'xlsx',
            'xls',
            'png',
            'zip',
            'key',
            'ppt',
            'pptx',
        ];
    }
}
