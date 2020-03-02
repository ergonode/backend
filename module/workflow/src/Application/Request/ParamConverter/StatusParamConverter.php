<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Request\ParamConverter;

use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class StatusParamConverter implements ParamConverterInterface
{
    /**
     * @var StatusRepositoryInterface
     */
    private StatusRepositoryInterface $repository;

    /**
     * @param StatusRepositoryInterface $repository
     */
    public function __construct(StatusRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        if ($configuration->getName()) {
            $parameter = $request->get($configuration->getName());
        } else {
            $parameter = $request->get('status');
        }

        if (null === $parameter) {
            throw new BadRequestHttpException('Route parameter "status" is missing');
        }

        if (!StatusId::isValid($parameter)) {
            if (!StatusCode::isValid($parameter)) {
                throw new BadRequestHttpException('Invalid status code format');
            }
            $parameter = StatusId::fromCode((new StatusCode($parameter))->getValue())->getValue();
        }

        $entity = $this->repository->load(new StatusId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Status by id "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Status::class === $configuration->getClass();
    }
}
