<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test;

use Ergonode\Core\Infrastructure\Service\DownloaderInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class TestDownloaderDecorator implements DownloaderInterface
{
    private DownloaderInterface $downloader;

    private KernelInterface $kernel;

    public function __construct(DownloaderInterface $downloader, KernelInterface $kernel)
    {
        $this->downloader = $downloader;
        $this->kernel = $kernel;
    }


    public function download(string $url, array $headers = [], array $acceptedContentTypes = null): string
    {
        if (false === strpos($url, 'file://')) {
            return $this->downloader->download($url, $headers, $acceptedContentTypes);
        }

        $path = $this->kernel->getProjectDir().'/'.substr($url, 6);

        return file_get_contents($path);
    }
}
