<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\Request\ParamConverter;

use Ergonode\Category\Domain\Entity\CategoryTree;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryTreeParamConverter implements ParamConverterInterface
{
    private TreeRepositoryInterface $repository;

    public function __construct(TreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('tree');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "tree" is missing');
        }

        if (!CategoryTreeId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid category tree ID');
        }

        $entity = $this->repository->load(new CategoryTreeId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Category tree by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    public function supports(ParamConverter $configuration): bool
    {
        return CategoryTree::class === $configuration->getClass();
    }
}
