<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Subscriber;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

/**
 */
class LocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param Security                $security
     * @param TranslatorInterface     $translator
     * @param UserRepositoryInterface $repository
     */
    public function __construct(Security $security, TranslatorInterface $translator, UserRepositoryInterface $repository)
    {
        $this->security = $security;
        $this->repository = $repository;
        $this->translator = $translator;
    }

    /**
     * @param KernelEvent $event
     *
     * @throws \ReflectionException
     */
    public function onKernelRequest(KernelEvent $event): void
    {
        $request = $event->getRequest();
        if ($this->security->getUser()) {
            $user = $this->repository->load(UserId::createFromUuid($this->security->getUser()->getId()));
            Assert::notNull($user, 'cant find user %s');
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
