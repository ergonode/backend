<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Request\ParamConverter;

use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class RoleParamConverter implements ParamConverterInterface
{
    /**
     * @var RoleRepositoryInterface
     */
    private RoleRepositoryInterface $roleRepository;

    /**
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('role');

        if (null === $parameter) {
            throw new BadRequestHttpException('Route parameter "role" is missing');
        }

        if (!RoleId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid role ID format');
        }

        $entity = $this->roleRepository->load(new RoleId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Role by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Role::class === $configuration->getClass();
    }
}
