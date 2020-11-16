<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Subscriber;

use Ergonode\Account\Domain\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use Webmozart\Assert\Assert;

class LocaleSubscriber implements EventSubscriberInterface
{
    private Security $security;

    private LocaleAwareInterface $localeAware;

    public function __construct(Security $security, LocaleAwareInterface $localeAware)
    {
        $this->security = $security;
        $this->localeAware = $localeAware;
    }

    /**
     * @throws \ReflectionException
     */
    public function onKernelRequest(KernelEvent $event): void
    {
        $request = $event->getRequest();
        if ($this->security->getUser()) {
            /** @var User $user */
            $user = $this->security->getUser();
            Assert::notNull($user, 'Cannot find user %s');
            $locale = strtolower($user->getLanguage()->getCode());
            $this->localeAware->setLocale($locale);
            $request->setLocale($locale);
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => [['onKernelRequest', 20]],
        ];
    }
}
