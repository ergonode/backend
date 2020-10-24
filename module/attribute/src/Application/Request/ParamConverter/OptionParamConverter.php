<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Domain\Entity\AbstractOption;

class OptionParamConverter implements ParamConverterInterface
{
    private OptionRepositoryInterface $repository;

    public function __construct(OptionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('option');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "option" is missing');
        }

        if (!AggregateId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid option ID');
        }

        $entity = $this->repository->load(new AggregateId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Option by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return AbstractOption::class === $configuration->getClass();
    }
}
