<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\RefreshToken\Doctrine;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Persistence\ObjectManager;
use Ergonode\Authentication\Application\RefreshToken\Doctrine\Mapping\RefreshTokenMetadataFactory;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Symfony\Contracts\Service\ResetInterface;
use Webmozart\Assert\Assert;

class RefreshTokenManager implements ObjectManager, ResetInterface
{
    private RefreshTokenRepositoryInterface $repository;
    private RefreshTokenMetadataFactory $metadataFactory;
    /**
     * @var RefreshToken[]
     */
    private array $persisted;
    /**
     * @var RefreshToken[]
     */
    private array $toRemove;

    public function __construct(
        RefreshTokenRepositoryInterface $repository,
        RefreshTokenMetadataFactory $metadataFactory
    ) {
        $this->repository = $repository;
        $this->metadataFactory = $metadataFactory;
        $this->clear();
    }

    public function reset(): void
    {
        $this->clear();
    }

    public function find($className, $id): ?RefreshToken
    {
        if (RefreshToken::class !== $className) {
            throw new \UnexpectedValueException('Only RefreshToken supported.');
        }

        return $this->repository->find($id);
    }

    public function persist($object): void
    {
        Assert::isInstanceOf($object, RefreshToken::class);

        $id = spl_object_hash($object);
        $this->persisted[$id] = $object;
    }

    public function remove($object): void
    {
        Assert::isInstanceOf($object, RefreshToken::class);

        $id = spl_object_hash($object);
        $this->toRemove[$id] = $object;
    }

    public function merge($object)
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function clear($objectName = null): void
    {
        if (null !== $objectName && RefreshToken::class !== $objectName) {
            throw new \UnexpectedValueException('Not supported objectName');
        }

        $this->persisted = [];
        $this->toRemove = [];
    }

    public function detach($object): void
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function refresh($object): void
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function flush(): void
    {
        foreach ($this->persisted as $peristed) {
            $this->repository->insert($peristed);
        }
        foreach ($this->toRemove as $toRemove) {
            $this->repository->delete($toRemove);
        }
    }

    public function getRepository($className): RefreshTokenRepositoryInterface
    {
        if (RefreshToken::class !== $className) {
            throw new \UnexpectedValueException('Only RefrehToken supported.');
        }

        return $this->repository;
    }

    public function getClassMetadata($className): ClassMetadata
    {
        return $this->metadataFactory->getMetadataFor($className);
    }

    public function getMetadataFactory(): ClassMetadataFactory
    {
        return $this->metadataFactory;
    }

    public function initializeObject($obj): void
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function contains($object): bool
    {
        $id = spl_object_hash($object);
        $persisted = $this->persisted[$id] ?? null;
        if (!$persisted) {
            return false;
        }
        if ($persisted !== $object) {
            $this->persisted[$id] = null;

            return false;
        }

        return true;
    }
}
