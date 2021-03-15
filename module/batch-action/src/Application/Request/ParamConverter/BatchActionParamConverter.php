<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;

class BatchActionParamConverter implements ParamConverterInterface
{
    private BatchActionRepositoryInterface $repository;

    public function __construct(BatchActionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        if ($configuration->getName()) {
            $parameter = $request->get($configuration->getName());
        } else {
            $parameter = $request->get('action');
        }

        if (null === $parameter) {
            throw new BadRequestHttpException(sprintf('Request parameter "%s" is missing', $parameter));
        }

        if (!BatchActionId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid batch action ID');
        }

        $entity = $this->repository->load(new BatchActionId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Batch action by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return BatchAction::class === $configuration->getClass();
    }
}
