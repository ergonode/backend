<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Infrastructure\Provider;

use Ergonode\Multimedia\Infrastructure\Provider\MultimediaExtensionProvider;
use PHPUnit\Framework\TestCase;

class MultimediaExtensionProviderTest extends TestCase
{
    private MultimediaExtensionProvider $provider;

    protected function setUp(): void
    {
        $this->provider = new MultimediaExtensionProvider();
    }

    public function testProviderDictionary(): void
    {
        $dictionary = [
            'bmp',
            'csv',
            'doc',
            'docx',
            'eps',
            'gif',
            'jpeg',
            'jpg',
            'key',
            'ods',
            'odt',
            'pdf',
            'png',
            'ppt',
            'pptx',
            'svg',
            'tif',
            'tiff',
            'txt',
            'webp',
            'xls',
            'xlsx',
            'zip',
        ];

        $result = $this->provider->dictionary();

        self::assertEquals($dictionary, $result);
    }

    public function testProviderMimeDictionary(): void
    {
        $dictionary = [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/gif',
            'image/jpeg',
            'image/webp',
            'image/svg+xml',
            'application/postscript',
            'application/vnd.oasis.opendocument.spreadsheet',
            'application/vnd.oasis.opendocument.text',
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

        $result = $this->provider->mimeDictionary();

        sort($dictionary);

        self::assertEquals($dictionary, $result);
    }
}
