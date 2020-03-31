<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Request\ParamConverter;

use Ergonode\Product\Domain\ValueObject\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 */
class ProductTypeParamConverter implements ParamConverterInterface
{

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('type');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "type" is missing');
        }

        if (!ProductType::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid product type');
        }

        $value = new ProductType($parameter);

        $request->attributes->set($configuration->getName(), $value);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return ProductType::class === $configuration->getClass();
    }
}
