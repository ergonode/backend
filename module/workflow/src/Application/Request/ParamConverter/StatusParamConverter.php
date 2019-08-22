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
     * @param StatusRepositoryInterface $propertiesRepository
     */
    public function __construct(StatusRepositoryInterface $propertiesRepository)
    {
        $this->repository = $propertiesRepository;
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
        $properties = $request->get('status');


        if (null === $properties) {
            throw new BadRequestHttpException('Route attribute is missing');
        }

        if (!StatusId::isValid($properties)) {
            throw new BadRequestHttpException('Invalid uuid format');
        }

        $properties = $this->repository->load(new StatusId($properties));

        if (null === $properties) {
            throw new NotFoundHttpException(\sprintf('%s object not found.', $configuration->getClass()));
        }

        $request->attributes->set($configuration->getName(), $properties);
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
