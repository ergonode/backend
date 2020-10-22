<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Request\ParamConverter;

use Ergonode\ProductCollection\Domain\Entity\ProductCollectionType;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionTypeRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductCollectionTypeParamConverter implements ParamConverterInterface
{
    /**
     * @var ProductCollectionTypeRepositoryInterface
     */
    private ProductCollectionTypeRepositoryInterface $productCollectionRepository;

    /**
     * ProductCollectionParamConverter constructor.
     *
     * @param ProductCollectionTypeRepositoryInterface $productCollectionRepository
     */
    public function __construct(ProductCollectionTypeRepositoryInterface $productCollectionRepository)
    {
        $this->productCollectionRepository = $productCollectionRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('type');

        if (null === $parameter) {
            throw new BadRequestHttpException('Route parameter "type" is missing');
        }

        if (!ProductCollectionTypeId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid product collection type ID format');
        }

        $entity = $this->productCollectionRepository->load(new ProductCollectionTypeId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Product collection type ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return ProductCollectionType::class === $configuration->getClass();
    }
}
