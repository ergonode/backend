<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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

    /**
     * {@inheritdoc}
     */
    public function reset(): void
    {
        $this->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function find($className, $id): ?RefreshToken
    {
        if (RefreshToken::class !== $className) {
            throw new \UnexpectedValueException('Only RefreshToken supported.');
        }

        return $this->repository->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function persist($object): void
    {
        Assert::isInstanceOf($object, RefreshToken::class);
        if ($object->getId()) {
            throw new \UnexpectedValueException('Token already persisted.');
        }

        $id = spl_object_hash($object);
        $this->persisted[$id] = $object;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($object): void
    {
        Assert::isInstanceOf($object, RefreshToken::class);
        if (!$object->getId()) {
            throw new \UnexpectedValueException('Token is not persisted.');
        }

        $id = spl_object_hash($object);
        $this->toRemove[$id] = $object;
    }

    /**
     * {@inheritdoc}
     */
    public function merge($object): void
    {
        throw new \BadMethodCallException('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function clear($objectName = null): void
    {
        if (null !== $objectName && RefreshToken::class !== $objectName) {
            throw new \UnexpectedValueException('Not supported objectName.');
        }

        $this->persisted = [];
        $this->toRemove = [];
    }

    /**
     * {@inheritdoc}
     */
    public function detach($object): void
    {
        throw new \BadMethodCallException('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function refresh($object): void
    {
        throw new \BadMethodCallException('Not implemented.');
    }

    /**
     * {@inheritdoc}
     */
    public function flush(): void
    {
        foreach ($this->persisted as $persisted) {
            $this->repository->insert($persisted);
        }
        foreach ($this->toRemove as $toRemove) {
            $this->repository->delete($toRemove);
        }
        $this->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function getRepository($className): RefreshTokenRepositoryInterface
    {
        if (RefreshToken::class !== $className) {
            throw new \UnexpectedValueException('Only RefreshToken supported.');
        }

        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassMetadata($className): ClassMetadata
    {
        if (RefreshToken::class !== $className) {
            throw new \UnexpectedValueException('Only RefreshToken supported.');
        }

        return $this->metadataFactory->getMetadataFor($className);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFactory(): ClassMetadataFactory
    {
        return $this->metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function initializeObject($obj): void
    {
    }

    /**
     * {@inheritdoc}
     */
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
