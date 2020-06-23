<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Request\ParamConverter;

use Ergonode\Multimedia\Domain\Entity\Avatar;
use Ergonode\Multimedia\Domain\Repository\AvatarRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Storage\ResourceStorageInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class AvatarParamConverter implements ParamConverterInterface
{
    /**
     * @var AvatarRepositoryInterface
     */
    private AvatarRepositoryInterface $repository;

    /**
     * @var ResourceStorageInterface
     */
    private ResourceStorageInterface $avatarStorage;

    /**
     * @param AvatarRepositoryInterface $repository
     * @param ResourceStorageInterface  $avatarStorage
     */
    public function __construct(
        AvatarRepositoryInterface $repository,
        ResourceStorageInterface $avatarStorage
    ) {
        $this->repository = $repository;
        $this->avatarStorage = $avatarStorage;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('avatar');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "avatar" is missing');
        }

        if (!AvatarId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid avatar ID');
        }

        $entity = $this->repository->load(new AvatarId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Avatar by ID "%s" not found', $parameter));
        }

        if (!$this->avatarStorage->has($entity->getFileName())) {
            throw new ConflictHttpException('The file does not exist.');
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Avatar::class === $configuration->getClass();
    }
}
