<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Service\Decorator;

use Symfony\Component\HttpKernel\KernelInterface;
use Ergonode\Core\Infrastructure\Service\DownloaderInterface;

/**
 */
class CacheDownloaderDecorator implements DownloaderInterface
{
    /**
     * @var DownloaderInterface
     */
    private DownloaderInterface $downloader;

    /**
     * @var string
     */
    private string $directory;

    /**
     * @param DownloaderInterface $downloader
     * @param KernelInterface     $appKernel
     */
    public function __construct(DownloaderInterface $downloader, KernelInterface $appKernel)
    {
        $this->downloader = $downloader;
        $this->directory = sprintf('%s/var/downloader', $appKernel->getProjectDir());
    }

    /**
     * @param string $url
     *
     * @return string|null
     */
    public function download(string $url): ?string
    {
        $data = parse_url($url);

        $filename = sprintf('%s/%s%s', $this->directory, $data['host'], $data['path']);

        if (!file_exists($filename)) {
            $content = $this->downloader->download($url);
            $this->saveFile($filename, $content);

            return $content;
        }
        return file_get_contents($filename);
    }

    /**
     * @param $filename
     * @param $contents
     */
    public function saveFile($filename, $contents): void
    {
        $parts = explode('/', $filename);
        $file = array_pop($parts);
        $dir = '';
        foreach ($parts as $part) {
            if (!is_dir($dir .= "/$part")) {
                mkdir($dir);
            }
        }

        file_put_contents("$dir/$file", $contents);
    }
}
