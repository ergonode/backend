<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Service;

use Symfony\Component\HttpFoundation\Response;
use Ergonode\Core\Infrastructure\Exception\DownloaderException;
use Psr\Log\LoggerInterface;
use Ergonode\Core\Infrastructure\Exception\FileNotFoundDownloaderException;
use Ergonode\Core\Infrastructure\Exception\AccessDeniedDownloaderException;
use Ergonode\Core\Infrastructure\Exception\BadRequestDownloaderException;

/**
 * @deprecated
 */
class CurlDownloader implements DownloaderInterface
{
    private const AGENT = 'Mozilla/5.0 '
    .'(Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36';

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Header[] $headers
     *
     * @throws DownloaderException
     */
    public function download(string $url, array $headers = [], array $acceptedContentTypes = []): string
    {
        if (!empty($acceptedContentTypes)) {
            throw new DownloaderException('CurlDownloader not supported param `acceptedContentTypes`');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, self::AGENT);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (Response::HTTP_OK === $code && $content) {
            return $content;
        }

        $this->logger->info(sprintf('Can\'t download file %s, code %s', $url, $code));

        switch ($code) {
            case Response::HTTP_NOT_FOUND:
                throw new FileNotFoundDownloaderException($url);
            case Response::HTTP_FORBIDDEN:
            case Response::HTTP_UNAUTHORIZED:
                throw new AccessDeniedDownloaderException($url);
            case Response::HTTP_BAD_REQUEST:
                throw new BadRequestDownloaderException($url);
            default:
                throw new DownloaderException(sprintf('Can\'t download file from %s', $url));
        }
    }
}
