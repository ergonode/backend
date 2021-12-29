<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Service;

use Ergonode\Core\Infrastructure\Exception\DownloaderException;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Ergonode\Core\Infrastructure\Exception\FileNotFoundDownloaderException;
use Ergonode\Core\Infrastructure\Exception\AccessDeniedDownloaderException;
use Ergonode\Core\Infrastructure\Exception\BadRequestDownloaderException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ConnectException;

class GuzzleDownloader implements DownloaderInterface
{
    private const TIMEOUT = 1;

    private LoggerInterface $logger;

    private Client $client;

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param Header[] $headers
     *
     * @throws DownloaderException
     */
    public function download(string $url, array $headers = []): string
    {
        try {
            $response = $this->client->get(
                $url,
                [
                    'timeout' => self::TIMEOUT,
                    'headers' => $this->mapHeaders($headers),
                ]
            );

            $code = $response->getStatusCode();
            $content = $response->getBody()->getContents();
        } catch (ConnectException $exception) {
            $this->logger->error($exception);
            throw new DownloaderException(sprintf('Can\'t connect to url: %s', $url));
        } catch (GuzzleException $exception) {
            $this->logger->error($exception);
            throw new DownloaderException(sprintf('Can\'t download file from url: %s', $url));
        }

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

    /**
     * @param Header[] $headers
     *
     * @return array
     */
    private function mapHeaders(array $headers): array
    {
        $result = [];
        foreach ($headers as $header) {
            $result[$header->getKey()] = $header->getValue();
        }

        return $result;
    }
}
