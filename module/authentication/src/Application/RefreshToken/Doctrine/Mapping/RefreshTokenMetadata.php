<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\RefreshToken\Doctrine\Mapping;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;

class RefreshTokenMetadata implements ClassMetadata
{
    public function getName(): string
    {
        return RefreshToken::class;
    }

    public function getIdentifier()
    {
        return ['id'];
    }

    public function getReflectionClass()
    {
        return new \ReflectionClass(RefreshToken::class);
    }

    public function isIdentifier($fieldName)
    {
        return 'id' === $fieldName;
    }

    public function hasField($fieldName): bool
    {
        return in_array($fieldName, $this->getFieldNames());
    }

    public function hasAssociation($fieldName)
    {
        return false;
    }

    public function isSingleValuedAssociation($fieldName)
    {
        return false;
    }

    public function isCollectionValuedAssociation($fieldName)
    {
        return false;
    }

    public function getFieldNames()
    {
        return ['id', 'username', 'valid', 'refreshToken'];
    }

    public function getIdentifierFieldNames()
    {
        return ['id'];
    }

    public function getAssociationNames()
    {
        return [];
    }

    public function getTypeOfField($fieldName)
    {
        if (!$this->hasField($fieldName)) {
            throw new \UnexpectedValueException('No such field.');
        }
        if ('valid' === $fieldName) {
            return 'datetime';
        }

        return 'string';
    }

    public function getAssociationTargetClass($assocName)
    {
        throw new \Exception('No association');
    }

    public function isAssociationInverseSide($assocName)
    {
        return false;
    }

    public function getAssociationMappedByTargetField($assocName)
    {
        throw new \Exception('No association');
    }

    public function getIdentifierValues($object): array
    {
        return ['id'];
    }
}
