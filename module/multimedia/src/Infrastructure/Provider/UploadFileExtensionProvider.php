<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Provider;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileExtensionProvider
{
    private MultimediaExtensionProvider $provider;

    public function __construct(MultimediaExtensionProvider $provider)
    {
        $this->provider = $provider;
    }

    public function getExtension(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        if (empty($extension) || '.' === $extension) {
            $extension = $file->guessExtension();
        }

        if (!in_array($extension, $this->provider->dictionary())) {
            throw new \RuntimeException(sprintf('File extension %s is not supported', $extension));
        }

        return $extension;
    }
}
