<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\RefreshToken\Doctrine\Mapping;

use Doctrine\Persistence\Mapping\ClassMetadata;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;

class RefreshTokenMetadata implements ClassMetadata
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return RefreshToken::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): array
    {
        return ['id'];
    }

    /**
     * {@inheritdoc}
     */
    public function getReflectionClass(): \ReflectionClass
    {
        return new \ReflectionClass(RefreshToken::class);
    }

    /**
     * {@inheritdoc}
     */
    public function isIdentifier($fieldName): bool
    {
        return 'id' === $fieldName;
    }

    /**
     * {@inheritdoc}
     */
    public function hasField($fieldName): bool
    {
        return in_array($fieldName, $this->getFieldNames());
    }

    /**
     * {@inheritdoc}
     */
    public function hasAssociation($fieldName): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isSingleValuedAssociation($fieldName): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isCollectionValuedAssociation($fieldName): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldNames(): array
    {
        return ['id', 'username', 'valid', 'refreshToken'];
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifierFieldNames(): array
    {
        return ['id'];
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociationNames(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeOfField($fieldName): string
    {
        if (!$this->hasField($fieldName)) {
            throw new \UnexpectedValueException('No such field.');
        }
        if ('valid' === $fieldName) {
            return 'datetime';
        }

        return 'string';
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociationTargetClass($assocName): void
    {
        throw new \Exception('No association');
    }

    /**
     * {@inheritdoc}
     */
    public function isAssociationInverseSide($assocName): bool
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociationMappedByTargetField($assocName): void
    {
        throw new \Exception('No association');
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifierValues($object): array
    {
        return ['id'];
    }
}
