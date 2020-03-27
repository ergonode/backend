<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Request;

use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class UnitParamConverter implements ParamConverterInterface
{
    /**
     * @var UnitRepositoryInterface
     */
    private UnitRepositoryInterface $unitRepository;

    /**
     *
     * @param UnitRepositoryInterface $unitRepository
     */
    public function __construct(UnitRepositoryInterface $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('unit');

        if (null === $parameter) {
            throw new BadRequestHttpException('Route parameter "unit" is missing');
        }

        if (!UnitId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid unit ID format');
        }

        $entity = $this->unitRepository->load(new UnitId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Unit ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Unit::class === $configuration->getClass();
    }
}
