<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Application\Request\ParamConverter;

use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class SegmentParamConverter implements ParamConverterInterface
{
    /**
     * @var SegmentRepositoryInterface
     */
    private SegmentRepositoryInterface $repository;

    /**
     * @param SegmentRepositoryInterface $repository
     */
    public function __construct(SegmentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('segment');

        if (null === $parameter) {
            throw new BadRequestHttpException('Route parameter "segment" is missing');
        }

        if (!SegmentId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid segment ID format');
        }

        $entity = $this->repository->load(new SegmentId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Segment by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Segment::class === $configuration->getClass();
    }
}
