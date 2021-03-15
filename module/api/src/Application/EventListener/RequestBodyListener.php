<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\EventListener;

use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestBodyListener
{
    private const METHODS = [
        Request::METHOD_PUT,
        Request::METHOD_POST,
        Request::METHOD_PATCH,
        Request::METHOD_DELETE,
    ];

    private const CONTENT_TYPES = ['json'];

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function __invoke(GetResponseEvent $event): void
    {
        $request = $event->getRequest();

        $contentType = $request->getContentType() ?? 'json';
        $method = $request->getMethod();
        $content = $request->getContent();

        if (empty($content) ||
            !in_array($contentType, self::CONTENT_TYPES, true) ||
            !in_array($method, self::METHODS, true)) {
            return;
        }

        $data = $this->serializer->deserialize($content, 'array');

        $request->request = new ParameterBag($data);
    }
}
