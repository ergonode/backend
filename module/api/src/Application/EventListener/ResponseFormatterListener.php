<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\EventListener;

use Ergonode\Api\Application\Response\AbstractResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Ergonode\Core\Application\Serializer\SerializerInterface;

class ResponseFormatterListener
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function __invoke(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        if (!$response instanceof AbstractResponse) {
            return;
        }

        $content = $response->getContent();

        if (null !== $content && !is_string($content)) {
            $content = $this->serializer->serialize($content);
            $response->setContent($content);
            $event->setResponse($response);
        }
    }
}
