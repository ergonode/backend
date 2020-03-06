<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Request\ParamConverter;

use Ergonode\Attribute\Domain\Entity\AttributeGroup;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class AttributeGroupParamConverter implements ParamConverterInterface
{
    /**
     * @var AttributeGroupRepositoryInterface
     */
    private AttributeGroupRepositoryInterface $repository;

    /**
     * @param AttributeGroupRepositoryInterface $repository
     */
    public function __construct(AttributeGroupRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('group');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "group" is missing');
        }

        if (!AttributeGroupId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid attribute ID');
        }

        $entity = $this->repository->load(new AttributeGroupId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Attribute group by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return AttributeGroup::class === $configuration->getClass();
    }
}
