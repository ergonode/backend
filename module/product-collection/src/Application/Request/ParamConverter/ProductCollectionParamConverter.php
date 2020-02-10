<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Request\ParamConverter;

use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class ProductCollectionParamConverter implements ParamConverterInterface
{
    /**
     * @var ProductCollectionRepositoryInterface
     */
    private ProductCollectionRepositoryInterface $productCollectionRepository;

    /**
     * ProductCollectionParamConverter constructor.
     *
     * @param ProductCollectionRepositoryInterface $productCollectionRepository
     */
    public function __construct(ProductCollectionRepositoryInterface $productCollectionRepository)
    {
        $this->productCollectionRepository = $productCollectionRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('collection');

        if (null === $parameter) {
            throw new BadRequestHttpException('Route parameter "collection" is missing');
        }

        if (!ProductCollectionId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid collection ID format');
        }

        $entity = $this->productCollectionRepository->load(new ProductCollectionId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Product collection ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return ProductCollection::class === $configuration->getClass();
    }
}
