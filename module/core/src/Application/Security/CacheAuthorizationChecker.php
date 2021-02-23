<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Security;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Service\ResetInterface;

class CacheAuthorizationChecker implements AuthorizationCheckerInterface, ResetInterface
{
    /**
     * @var bool[][]
     */
    private array $cache;
    private AuthorizationCheckerInterface $authorizationChecker;
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $tokenStorage
    ) {
        $this->cache = [];
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function isGranted($attributes, $subject = null): bool
    {
        $token = $this->tokenStorage->getToken();
        if (!$token || !$identifier = $this->supportsCaching($attributes, $subject)) {
            return $this->authorizationChecker->isGranted($attributes, $subject);
        }
        $tokenHash = spl_object_hash($token);

        if (($this->cache[$tokenHash][$identifier]['token'] ?? null) !== $token) {
            $this->cache[$tokenHash][$identifier] = [
                'token' => $token,
                'isGranted' => $this->authorizationChecker->isGranted($attributes, $subject),
            ];
        }

        return $this->cache[$tokenHash][$identifier]['isGranted'];
    }

    /**
     * {@inheritdoc}
     */
    public function reset(): void
    {
        $this->cache = [];
    }

    /**
     * @param mixed $attributes
     * @param mixed $subject
     */
    private function supportsCaching($attributes, $subject): ?string
    {
        $id = null;
        if (is_string($attributes)) {
            $id .= "str_$attributes";
        } elseif (is_object($attributes)) {
            $id .= 'obj_'.spl_object_hash($attributes);
        } elseif (null === $attributes) {
            // nothing
        } else {
            return null;
        }
        if (is_string($subject)) {
            $id .= "_str_$subject";
        } elseif (is_object($subject)) {
            $id .= '_obj'.spl_object_hash($subject);
        } elseif (null === $subject) {
            // nothing
        } else {
            return null;
        }

        return $id;
    }
}
