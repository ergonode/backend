<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\NewRelic\Application\EventSubscriber;

use Ergonode\NewRelic\Application\NewRelic\NewRelicInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class RouteEventSubscriber implements EventSubscriberInterface
{
    private NewRelicInterface $newRelic;

    public function __construct(NewRelicInterface $newRelic)
    {
        $this->newRelic = $newRelic;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        $routeName = $event->getRequest()->attributes->get('_route');

        if (!$routeName) {
            return;
        }

        $this->newRelic->nameTransaction(
            $routeName,
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['onKernelRequest', 31],
        ];
    }
}
