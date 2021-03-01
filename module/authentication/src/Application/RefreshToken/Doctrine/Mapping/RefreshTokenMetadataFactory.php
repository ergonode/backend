<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\RefreshToken\Doctrine\Mapping;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Persistence\Mapping\MappingException;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;

class RefreshTokenMetadataFactory implements ClassMetadataFactory
{
    public function getAllMetadata(): array
    {
        return [new RefreshTokenMetadata()];
    }

    public function getMetadataFor($className)
    {
        if (RefreshToken::class !== $className) {
            throw new MappingException("$className not mapped");
        }

        return new RefreshTokenMetadata();
    }

    public function hasMetadataFor($className): bool
    {
        return $this->isTransient($className);
    }

    public function setMetadataFor($className, $class): void
    {
    }

    public function isTransient($className): bool
    {
        return RefreshToken::class === $className;
    }
}
