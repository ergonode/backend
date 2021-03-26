<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Connector;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\PostAccessToken;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

class Shopware6Connector
{
    private Configurator $configurator;

    private LoggerInterface $logger;

    private ?string $token;

    private \DateTimeInterface $expiresAt;

    public function __construct(Configurator $configurator, LoggerInterface $logger)
    {
        $this->configurator = $configurator;
        $this->logger = $logger;

        $this->token = null;
        $this->expiresAt = new \DateTimeImmutable();
    }

    /**
     * @return array|object|string|null
     *
     * @throws \Exception
     */
    public function execute(Shopware6Channel $channel, ActionInterface $action)
    {
        if ($this->token === null || $this->expiresAt <= (new \DateTime())) {
            $this->requestToken($channel);
        }

        return $this->request($channel, $action);
    }

    /**
     * @return array|object|string|null
     *
     * @throws \Exception
     */
    private function request(Shopware6Channel $channel, ActionInterface $action)
    {
        try {
            $config = [
                'base_uri' => $channel->getHost(),
            ];

            $this->configurator->configure($action, $this->token);
            if ($action->isLoggable()) {
                $this->generateRequestLog($action);
            }

            $client = new Client($config);

            $response = $client->send($action->getRequest());
            $contents = $this->resolveResponse($response);
            if ($action->isLoggable()) {
                $this->generateResponseLog($response, $contents);
            }

            return $action->parseContent($contents);
        } catch (GuzzleException $exception) {
            //todo log
            throw  $exception;
        } catch (\Exception $exception) {
            //todo log
            throw  $exception;
        }
    }

    /**
     * @throws \Exception
     */
    private function requestToken(Shopware6Channel $channel): void
    {
        $post = new PostAccessToken($channel);
        $data = $this->request($channel, $post);
        $this->token = $data['access_token'];
        $this->expiresAt = $this->calculateExpiryTime((int) $data['expires_in']);
    }

    private function calculateExpiryTime(int $expiresIn): \DateTimeInterface
    {
        $expiryTimestamp = (new \DateTime())->getTimestamp() + $expiresIn;

        return (new \DateTimeImmutable())->setTimestamp($expiryTimestamp);
    }

    private function resolveResponse(ResponseInterface $response): ?string
    {
        $statusCode = $response->getStatusCode();
        $contents = $response->getBody()->getContents();

        switch ($statusCode) {
            case Response::HTTP_OK:
            case Response::HTTP_CREATED:
            case Response::HTTP_ACCEPTED:
                return $contents;
            case Response::HTTP_NO_CONTENT:
                return null;
        }
        throw new \RuntimeException(sprintf('Unsupported response status "%s" ', $statusCode));
    }

    private function generateRequestLog(ActionInterface $action): void
    {
        $this->logger->debug(
            $action->getRequest()->getMethod().' '.$action->getRequest()->getUri()->getPath(),
            [
                'patch' => $action->getRequest()->getUri()->getPath(),
                'method' => $action->getRequest()->getMethod(),
                'headers' => $action->getRequest()->getHeaders(),
                'body' => $action->getRequest()->getBody()->getContents(),
                'query' => $action->getRequest()->getUri()->getQuery(),
            ]
        );
    }

    private function generateResponseLog(ResponseInterface $response, ?string $contents): void
    {
        $this->logger->debug(
            'RESPONSE',
            [
                'status' => $response->getStatusCode(),
                'body' => $contents,
            ]
        );
    }
}
