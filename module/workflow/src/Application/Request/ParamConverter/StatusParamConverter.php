<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Request\ParamConverter;

use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
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
    private $repository;

    /**
     * @param StatusRepositoryInterface $parameterRepository
     */
    public function __construct(StatusRepositoryInterface $parameterRepository)
    {
        $this->repository = $parameterRepository;
    }

    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('status');

        if (null === $parameter) {
            throw new BadRequestHttpException('Route parameter status is missing');
        }

        if (!StatusId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid status ID format');
        }

        $entity = $this->repository->load(new StatusId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(\sprintf('%s object not found.', $configuration->getClass()));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Status::class === $configuration->getClass();
    }
}
