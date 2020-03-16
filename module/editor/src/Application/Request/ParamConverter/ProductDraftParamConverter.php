<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Application\Request\ParamConverter;

use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class ProductDraftParamConverter implements ParamConverterInterface
{
    /**
     * @var ProductDraftRepositoryInterface
     */
    private ProductDraftRepositoryInterface $repository;

    /**
     * @param ProductDraftRepositoryInterface $repository
     */
    public function __construct(ProductDraftRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('draft');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "draft" is missing');
        }

        if (!ProductDraftId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid product draft ID');
        }

        $entity = $this->repository->load(new ProductDraftId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Product draft by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return ProductDraft::class === $configuration->getClass();
    }
}
