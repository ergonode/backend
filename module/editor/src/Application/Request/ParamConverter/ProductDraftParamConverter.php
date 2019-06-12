<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Application\Request\ParamConverter;

use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Editor\Domain\Entity\ProductDraftId;
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
    private $repository;

    /**
     * @param ProductDraftRepositoryInterface $repository
     */
    public function __construct(ProductDraftRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     *
     * @return void
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $attribute = $request->get('draft');

        if (null === $attribute) {
            throw new BadRequestHttpException('Route product is missing');
        }

        if (!ProductDraftId::isValid($attribute)) {
            throw new BadRequestHttpException('Invalid uuid format');
        }

        $template = $this->repository->load(new ProductDraftId($attribute));

        if (null === $template) {
            throw new NotFoundHttpException(\sprintf('%s object not found.', $configuration->getClass()));
        }

        $request->attributes->set($configuration->getName(), $template);
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return ProductDraft::class === $configuration->getClass();
    }
}
