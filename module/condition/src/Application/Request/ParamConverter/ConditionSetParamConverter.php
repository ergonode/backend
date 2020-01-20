<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Application\Request\ParamConverter;

use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class ConditionSetParamConverter implements ParamConverterInterface
{
    /**
     * @var ConditionSetRepositoryInterface
     */
    private ConditionSetRepositoryInterface $conditionSetRepository;

    /**
     * @param ConditionSetRepositoryInterface $conditionSetRepository
     */
    public function __construct(ConditionSetRepositoryInterface $conditionSetRepository)
    {
        $this->conditionSetRepository = $conditionSetRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('conditionSet');

        if (null === $parameter) {
            throw new BadRequestHttpException('Route parameter "conditionSet" is missing');
        }

        if (!ConditionSetId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid condition set ID format');
        }

        $entity = $this->conditionSetRepository->load(new ConditionSetId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Condition set by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return ConditionSet::class === $configuration->getClass();
    }
}
