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
use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

class LocaleSubscriber implements EventSubscriberInterface
{
    private Security $security;

    private TranslatorInterface $translator;

    public function __construct(Security $security, TranslatorInterface $translator)
    {
        $this->security = $security;
        $this->translator = $translator;
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
            $this->translator->setLocale($locale);
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
