<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\RefreshToken\Doctrine\Mapping;

use Doctrine\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Persistence\Mapping\MappingException;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;

class RefreshTokenMetadataFactory implements ClassMetadataFactory
{
    /**
     * {@inheritdoc}
     */
    public function getAllMetadata(): array
    {
        return [new RefreshTokenMetadata()];
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($className): RefreshTokenMetadata
    {
        if (RefreshToken::class !== $className) {
            throw new MappingException("$className not mapped");
        }

        return new RefreshTokenMetadata();
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadataFor($className): bool
    {
        return $this->isTransient($className);
    }

    /**
     * {@inheritdoc}
     */
    public function setMetadataFor($className, $class): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isTransient($className): bool
    {
        return RefreshToken::class === $className;
    }
}
